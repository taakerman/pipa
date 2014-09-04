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
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Analysis\Mean;

/**
 * Variance of image
 */
class Variance {
    /**
     * Calculates the variance of an image for each RGB channel
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image image to calculate variance for
     * @param array $means the means of the image (if already calculated, otherwise will be calculated first)
     * @return array variance for RGB channels
     */
    public static function calculate(Pipa $image, array $means = null) {
        $sums = array(0, 0, 0);
        
        if ($means === null) {
            $means = Mean::calculate($image);
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);

                $sums[0] += pow($color->r - $means[0], 2);
                $sums[1] += pow($color->g - $means[1], 2);
                $sums[2] += pow($color->b - $means[2], 2);
            }
        }
        
        $hw = ($height * $width) - 1;
        
        return array(
            $sums[0] / $hw, 
            $sums[1] / $hw, 
            $sums[2] / $hw
        );
    }
}
