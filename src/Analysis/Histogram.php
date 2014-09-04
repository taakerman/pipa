<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Analysis;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Filter\Desaturater;
use Taakerman\Pipa\Color\Color;

/**
 * Class for creating histograms
 * 
 * @link http://en.wikipedia.org/wiki/HSL_and_HSV
 * @link http://en.wikipedia.org/wiki/Image_histogram
 * @link http://en.wikipedia.org/wiki/Color_histogram
 */
class Histogram {
    /**
     * Calculate a histogram for an image
     * 
     * @param \Taakerman\Pipa\BaseImage $image the image to calculate a histogram for
     * @param int $bins the number of bins to distribute the image to
     * @param array $desaturationCoefficients which coefficients to use as a desaturation factor 
     * standard is Desaturate::$LUMINANCE;
     * @return array the histogram
     */
    public static function calculate(Pipa $image, $bins = 256, array $desaturationCoefficients = null) {
        $channel = array_fill(0, $bins, 0);
        
        if ($desaturationCoefficients === null) {
            $desaturationCoefficients = Desaturater::$LUMINANCE;
        }
        
        $binSize = $bins / 256.0;
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                
                $cLinear = Color::fromInt($value)->desaturate($desaturationCoefficients);

                // find the correct bin
                $bin = (int) floor($cLinear / $binSize);
                ++$channel[$bin];
            }
        }
        
        return $channel;
    }
}
