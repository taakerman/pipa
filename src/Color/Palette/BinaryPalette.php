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
use Taakerman\Pipa\Color\Palette\Palette;

/**
 * A binary palette (black and white)
 * 
 */
class BinaryPalette extends Palette {
    private static $colors;
    
    public function __construct() {
        // static instantiate colors
        if (!isset(self::$colors)) {
            self::$colors = array(
                'white' =>   Color::fromInt(0xFFFFFFFF),
                'black' =>   Color::fromInt(0x00000000)
            );
        }
        
        parent::__construct(self::$colors);
    }
}
