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

/**
 * Class for calculating mean of an image
 */
class Mean {
    /**
     * Calculate the mean of an image
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image
     * @return array mean of RGB channels
     */
    public static function calculate(Pipa $image) {
        $means = array(0, 0, 0);
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                
                $color = Color::fromInt($value);
                $means[0] += $color->r;
                $means[1] += $color->g;
                $means[2] += $color->b;
            }
        }
        
        $hw = $height * $width;
        
        return array(
            $means[0] / $hw, 
            $means[1] / $hw, 
            $means[2] / $hw
        );
    }
}
