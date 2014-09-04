<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Color\Palette;

use Taakerman\Pipa\Color\Color;

/**
 * A palette of colors, which can be populated with the preferred colors
 */
class Palette {
    private $colors;
    
    /**
     * Constructs the palette
     * 
     * @param array $colors an array of \Taakerman\Pipa\Color\Color
     */
    public function __construct(array $colors = array()) {
        $this->colors = $colors;
    }
    
    /**
     * Gets all the colors of the palette
     * @return array array of colors
     */
    public function getColors() {
        return $this->colors;
    }
    
    /**
     * Adds a color to the palette
     * 
     * @param \Taakerman\Pipa\Image\Color $color
     */
    function add(Color $color) {
        $this->colors[] = $color;
    }
}
