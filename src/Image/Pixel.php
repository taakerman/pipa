<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Image;

use Taakerman\Pipa\Color\Color;

/**
 * A simple object holding information about a pixel
 * Used by PixelIterator
 */
class Pixel {
    /**
     * The x coordinate of the pixel
     * @var int 
     */
    public $x;
    
    /**
     * The y coordinate of the pixel
     * @var int $y
     */
    public $y;
    
    /**
     * The pixel value
     * @var int $value 
     */
    public $value;
    
    function __construct($x, $y, $value) {
        $this->x = $x;
        $this->y = $y;
        $this->value = $value;
    }
    
    /**
     * Returns the pixel value as a Color
     * 
     * @return \Taakerman\Pipa\Color\Color
     */
    public function asColor() {
        return Color::fromInt($this->value);
    }
}
