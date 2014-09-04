<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Filter;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Filter\Desaturater;
use Taakerman\Pipa\Color\Palette\GrayPalette;
use Taakerman\Pipa\Filter\Quantization\PaletteDistanceQuantizer;
use Taakerman\Pipa\Filter\Quantizer;
use Taakerman\Pipa\Util\Distance\DistanceFunction;
use Taakerman\Pipa\Util\Distance\EuclideanDistance;
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Util\Gamma\GammaFunction;
use Taakerman\Pipa\Util\Gamma\SrgbGammaFunction;

/**
 * This class is used for grayscaling an image and has several algorithms
 * to do so
 * 
 * {@see \Taakerman\Pipa\Filter\Desaturater} class can also be used
 * to create grayscaled images fast and simple. Actually luminosity algorithm
 * uses that class directly with Desaturate::$LUMINANCE constants
 * 
 * For more in-depth exploration of the differencies have a look at 
 * {@link http://www.tannerhelland.com/3643/grayscale-image-algorithm-vb6/} and 
 * {@link http://www.johndcook.com/blog/2009/08/24/algorithms-convert-color-grayscale/}
 */
class Grayscaler {
    /**
     * This is an implementation of the gamma correcting Grayscale algorithm
     * suggested on wikipedia (2014). It is by far the most complex algorithm in this class 
     * and uses gamma correction to ensure stability in the grayscaling
     * 
     * @link http://en.wikipedia.org/wiki/Grayscale
     * @link http://en.wikipedia.org/wiki/Gamma_correction
     * @link http://www.w3.org/TR/WCAG20/#relativeluminancedef
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @param array $coefficients the coefficients to use (defaults to Rec709)
     * @param \Taakerman\Pipa\Util\Gamma\GammaFunction $gamma the gamma correction function (defaults to sRGB)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function grayscale(Pipa $image, Pipa $dst = null, array $coefficients = null, GammaFunction $gamma = null) {
        if ($dst === null) {
            // just create new
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        if ($coefficients === null) {
            // use Rec709
            $coefficients = Desaturater::$REC_709;
        }
        
        if ($gamma === null) {
            // use sRGB gamma correction
            $gamma = new SrgbGammaFunction();
        }
        
        $cache = array();

        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);

                if (isset($cache[$value])) {
                    $dst->setPixel($x, $y, $cache[$value]);
                    continue;
                }

                $color = Color::fromInt($value);

                // normalize to 0..1
                $norm = array(
                    $color->r / 255.0, 
                    $color->g / 255.0, 
                    $color->b / 255.0);

                // gamma expand
                $gE = array(
                    $gamma->expand($norm[0]), 
                    $gamma->expand($norm[1]), 
                    $gamma->expand($norm[2])
                );

                // calculate the linear coefficent
                $cLiniear = $gE[0] * $coefficients[0] +
                        $gE[1] * $coefficients[1] +
                        $gE[2] * $coefficients[2];

                // gamma compress and de-normalize (0..1 -> 0..255)
                $csRgb = (int) ($gamma->compress($cLiniear) * 255.0);

                $resultColor = Color::fromChannel($csRgb, $color->a)->toInt();
                $cache[$value] = $resultColor;
                $dst->setPixel($x, $y, $resultColor);
            }
        }
        
        return $dst;
    }
    
    // experimental fast implementation of the standard grayscale
    public static function grayscalef(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $cache = array();
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                
                if (isset($cache[$value])) {
                    $dst->setPixel($x, $y, $cache[$value]);
                    continue;
                }
                
                // get rgb channels and normalize from 0..255 to 0..1
                $a = ($value >> 24) & 0xFF;
                $r = (($value >> 16) & 0xFF) / 255.0;
                $g = (($value >> 8) & 0xFF) / 255.0;
                $b = ($value & 0xFF) / 255.0;
                
                // gamma expand
                $rG = $r <= 0.04045 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
                $gG = $g <= 0.04045 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
                $bG = $b <= 0.04045 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);
                
                // calculate the linear coefficient based on REC709 coefficients
                $cLiniear = $rG * 0.2126 +
                    $gG * 0.7152 +
                    $bG * 0.0722;
                
                
                // gamma compress and de-normalize (0..1 -> 0..255)
                $csRgb = (int) (($cLiniear <= 0.0031308 ? $cLiniear * 12.92 : 1.055 * pow($cLiniear, 1.0/2.4) - 0.055) * 255.0);

                // convert to pixel and set in dst
                $pixel = ($a << 24) | 
                        ($csRgb << 16) | 
                        ($csRgb <<  8) | 
                        ($csRgb);
                
                $cache[$value] = $pixel;
                $dst->setPixel($x, $y, $pixel);
            }
        }
        
        return $dst;
    }
    
    /**
     * A quantizing algorithm, from a grayscale palette.
     * Using 256 gray colors, each color in the image is set to the gray 
     * that is closest to the color.
     * 
     * Do note that this is quite slow, because there are 256 colors in the 
     * standard gray palette, using a palette with fewer colors, will 
     * drastically increase the performance of the algorithm.
     * 
     * If used with the GrayPalette then it is similar to the average() function
     * 
     * @see \Taakerman\Pipa\Filter\ImageQuantizer
     * @see \Taakerman\Pipa\Filter\Quantizer\PaletteDistanceQuantizer
     * @see \Taakerman\Pipa\Color\Palette\GrayPalette
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @param \Taakerman\Pipa\Util\Distance\DistanceFunction $distanceFunction the distance function (defaults to euclidean distance)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function quantize(Pipa $image, Pipa $dst = null, DistanceFunction $distanceFunction = null, Palette $palette = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }

        if ($distanceFunction === null) {
            // default to euclidean
            $distanceFunction = new EuclideanDistance();
        }
        
        if ($palette === null) {
            // default to gray palette
            $palette = new GrayPalette();
        }
        
        $quantizer = new PaletteDistanceQuantizer($distanceFunction, $palette);
        return Quantizer::quantize($image, $dst, $quantizer);
    }
    
    /**
     * A simple averaging algorithm where
     * gray = RGB / 3.0 
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function average(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $avg = (int) (array_sum($color->toRgb()) / 3.0);
                $dst->setPixel($x, $y, Color::fromChannel($avg, $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple ligtness algorithm
     * gray = (max(RGB) + min(RGB)) / 2.0
     * 
     * This algorithm is analogous to {@see Grayscaler::saturationHsl()})
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function lightness(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);

                $rgb = $color->toRgb();
                $avg = (int) floor((max($rgb) + min($rgb)) / 2.0);
                $dst->setPixel($x, $y, Color::fromChannel($avg, $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple algorithm, where the Luminance channel of the HSL color 
     * space is used as the gray level
     * gray = L
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image
     * @param \Taakerman\Pipa\Image\Pipa $dst
     * @return \Taakerman\Pipa\Image\PipaMemory
     */
    public static function saturationHsl(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $hsl = $color->toHsl();
                $dst->setPixel($x, $y, Color::fromChannel((int) ($hsl[2] * 255.0), $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple algorithm, where the Value channel of the HSV color 
     * space is used as the gray level.
     * gray = V
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image
     * @param \Taakerman\Pipa\Image\Pipa $dst
     * @return \Taakerman\Pipa\Image\PipaMemory
     */
    public static function saturationHsv(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $hsv = $color->toHsv();
                $dst->setPixel($x, $y, Color::fromChannel((int) ($hsv[2] * 255.0), $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple decomposition algorithm where
     * gray = max(RGB)
     * 
     * This algorithm is analogous to {@see Grayscaler::saturationHsv()})
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function decompositionMax(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $c = max($color->toRgb());
                $dst->setPixel($x, $y, Color::fromChannel($c, $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple decomposition algorithm where
     * gray = min(RGB)
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function decompositionMin(Pipa $image, Pipa $dst = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $c = min($color->toRgb());
                $dst->setPixel($x, $y, Color::fromChannel($c, $color->a)->toInt());
            }
        }
        
        return $dst;
    }
    
    /**
     * A simple desaturation of the image.
     * 
     * See wikipedia article on grayscale before June 19th, 2012 {@link http://en.wikipedia.org/w/index.php?title=Grayscale&oldid=498269781}
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to grayscale
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @param array $coefficients the coefficients to use (defaults to {@link \Taakerman\Pipa\Filter\Desaturate::$LUMINANCE})
     * @return \Taakerman\Pipa\Image\Pipa the grayscaled image
     */
    public static function luminosity(Pipa $image, Pipa $dst = null, array $coefficients = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        if ($coefficients === null) {
            // use Rec709
            $coefficients = Desaturater::$LUMINANCE;
        }
        
        // just delegate to Desaturate ;-)
        return Desaturater::desaturate($image, $dst, $coefficients);
    }
}
