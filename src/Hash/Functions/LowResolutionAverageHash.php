<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Hash\Functions;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Hash\ImageHash;
use Taakerman\Pipa\Transform\Interpolate\NearestNeighbor;
use Taakerman\Pipa\Filter\Desaturater;
use Taakerman\Pipa\Color\Color;

use Exception;

/**
 * This hash uses a Nearest Neighbor Scaling function, which is 
 * very fast for scaling however the quality of scale is poor when compared to bilinear or bicubic 
 * scaling. 
 * 
 * This results in some differences in the hash if compared to 
 * the article. However as the scaling is only used to reduce resolution of the image, 
 * NN suffices for this method to work properly (and also quite fast)
 * 
 */
class LowResolutionAverageHash {
    /**
     * Calculate an ImageHash of the image
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to calculate the hash for
     * @param type $bitSize the number of bits to store in the hash
     * @return \Taakerman\Pipa\Hash\ImageHash the image hash
     * @throws Exception if bitsize is less than 4 (64 is default)
     */
    public static function hash(Pipa $image, $bitSize = 64) {
        if ($bitSize < 4) {
            // well I just don't see the point
            throw new Exception('Bitsizes less than 4 not allowed');
        }
        
        $wh = (int) (ceil(sqrt($bitSize)));
        $lowRes = NearestNeighbor::interpolate($image, $wh, $wh);

        $values = array_fill(0, $bitSize, 0);
        $coefficients = Desaturater::$LUMINANCE;
        
        for ($x = 0; $x < $wh; ++$x) {
            for ($y = 0; $y < $wh; ++$y) {
                $value = $lowRes->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $idx = ($wh * $x) + $y;
                $values[$idx] = $color->desaturate($coefficients);
            }
        }

        // sum the values divided by $bitSize
        // to find the average
        $avg = (int) floor(array_sum($values) / pow($wh, 2));
        
        // now there are $bitSize colors
        // just set a 0 or 1 depending on wheter the color is above or below avg
        $bitString = '';
        array_walk($values, 
            function($value) use (&$bitString, $avg) {
                $bitString .= ($value < $avg) ? '0' : '1'; 
            }
        );

        return ImageHash::fromBitString($bitString);
    }
}
