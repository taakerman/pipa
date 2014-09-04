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
use Taakerman\Pipa\Analysis\ColorMap;

/**
 * Count the number of unique colors in the image
 */
class ColorCount {
    /**
     * Count the number of colors in the image
     * Note: this may require a great deal of memory if used on natural images
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image
     * @return int the number of colors
     */
    public static function calculate(Pipa $image) {
        return count(ColorMap::calculate($image));
    }
}
