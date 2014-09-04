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
 * A palette of only grayscale colors
 * 
 */
class GrayPalette extends Palette {
    private static $colors;
    
    public function __construct() {
        if (!isset(self::$colors)) {
            self::$colors = array();
            
            for ($i = 0; $i < 256; ++$i) {
                self::$colors[] = Color::fromChannel($i);
            }
        }
        
        parent::__construct(self::$colors);
    }
}
