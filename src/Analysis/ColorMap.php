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
 * A Map of the colors in an image
 */
class ColorMap {
    /**
     * Creates a map of the colors in the image
     * Note: this may require a great deal of memory if used on images 
     * with high number of colors, e.g. natural images
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image
     * @param bool $sort sorts the colors by frequency descending
     * @return array array with colors as keys
     */
    public static function calculate(Pipa $image, $sort = false) {
        $map = array();
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                if (!isset($map[$value])) {
                    $map[$value] = 1;
                } else {
                    ++$map[$value];
                }
            }
        }
        
        if ($sort) {
            asort($map);
        }
        
        return $map;
    }
}
