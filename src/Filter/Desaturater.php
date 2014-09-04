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
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Color\Color;

/**
 * Desaturate an image by a linear transform from the RGB channels to a single 
 * channel
 * 
 */
class Desaturater {

    /**
     * Current recommended coefficients for grayscaling
     * 
     * @var array Rec. 709 coefficients
     * {@link http://en.wikipedia.org/wiki/Rec._709}
     */
    public static $REC_709 = array(0.2126, 0.7152, 0.0722);
    
    // http://en.wikipedia.org/wiki/Rec._601
    // http://en.wikipedia.org/wiki/Luma_%28video%29
    // used by PHOTOSHOP as a simple desaturation of luminance
    /**
     * Old(er) recommended coefficients for grayscaling
     * Also used in converting RGB to YIQ
     * 
     * @var array Rec 601 coefficients
     * {@link http://en.wikipedia.org/wiki/Rec._601} 
     * {@link http://en.wikipedia.org/wiki/YIQ}
     * {@link http://en.wikipedia.org/wiki/Luma_%28video%29}
     */
    public static $REC_601 = array(0.299, 0.587, 0.114);
    
    /**
     * SMPTE RP 145 primaries/SMPTE 240M
     * 
     * @var array SMPTE C coefficients
     * {@link http://en.wikipedia.org/wiki/Luma_%28video%29}
     */
    public static $SMPTE_C = array(0.212, 0.701, 0.087);
    
    /**
     * Used to perform fast grayscaling/desaturation
     * GIMP used this in ye olden days to grayscale, of course gegl 
     * has a much more complex algorithm  (gegl:c2g) ;-)
     * 
     * See wikipedia article on grayscale before June 19th, 2012 {@link http://en.wikipedia.org/w/index.php?title=Grayscale&oldid=498269781}
     * 
     * Maybe this one was used by PS also? {@link http://stackoverflow.com/questions/9839013/how-to-convert-an-image-to-gray-scale/9839346#9839346}
     * GIMP {@link http://www.johndcook.com/blog/2009/08/24/algorithms-convert-color-grayscale/}
     * @var array approximate Rec 601 
     */
    public static $LUMINANCE = array(0.3, 0.59, 0.11);
    
    /**
     * Desaturates an image using the coefficients
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to transform
     * @param array $coefficients the coefficients to use
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (or a new will be created)
     * @param bool $preserveAlpha if true, then alpha channel is preserved (default is true)
     * @return \Taakerman\Pipa\Image\Pipa $dst or a new BaseImage
     */
    public static function desaturate(Pipa $image, Pipa $dst = null, array $coefficients = null) {
        if ($dst === null) {
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        if ($coefficients === null) {
            // use ye old luminance coefficients
            $coefficients = self::$LUMINANCE;
        }

        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                // calculate the linear coefficent and set the value
                $c = $color->desaturate($coefficients);
                $dst->setPixel($x, $y, Color::fromChannel($c, $color->a)->toInt());
            }
        }
        
        return $dst;
    }
}
