<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Transform\Interpolate;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Image\PipaMemory;

/**
 * Nearest Neighbor interpolation implementation
 */
class NearestNeighbor {
    public static function interpolate(Pipa $image, $width, $height) {
        $dst = PipaMemory::fromSize($width, $height);
        $ratioX = $image->getWidth() / $width;
        $ratioY = $image->getHeight() / $height;

        // loop the destination picture
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $px = (int) floor($x * $ratioX);
                $py = (int) floor($y * $ratioY);

                $pixel = $image->getPixel($px, $py);
                $dst->setPixel($x, $y, $pixel);
            }
        }
        
        return $dst;
    }
}