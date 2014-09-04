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

/**
 * BestFit tries to maximize the image size within the given bounds
 * 
 */
class BestFit {
    /**
     * Calculates the best fit of an image into a new width and height
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to calculate for
     * @param int $maxWidth the new maximum width
     * @param int $maxHeight the new maximum height
     * @param bool $allowUpscale allow upscaling of the image
     * @return array array with the new width, height, ratio
     */
    public static function calculate(Pipa $image, $maxWidth, $maxHeight, $allowUpscale = true) {
        $w = $image->getWidth();
        $h = $image->getHeight();
        
        if ($w == $maxWidth && $h == $maxHeight) {
            // already at max w&h
            return array($w, $h, 0);
        } else if ($maxWidth > $w && $maxHeight > $h && !$allowUpscale) {
            return array($w, $h, 0);
        }
        
        $ratio = min( array($maxWidth / $w, $maxHeight / $h) );
        $newWidth = $ratio * $w;
        $newHeight = $ratio * $h;

        // round and check for rounding errors
        $newWidth = round($newWidth);
        $newHeight = round($newHeight);
                
        $newWidth = ($newWidth <= $maxWidth) ? $newWidth : $maxWidth;
        $newHeight = ($newHeight <= $maxHeight) ? $newHeight : $maxHeight;

        return array((int) $newWidth, (int) $newHeight, (float) $ratio);
    }
}
