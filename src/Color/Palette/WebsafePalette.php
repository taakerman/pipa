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
 * A palette of the websafe colors as described on wikipedia 2014
 * {@link http://en.wikipedia.org/wiki/Web_colors}
 */
class WebsafePalette extends Palette {
    private static $colors;
    
    public function __construct() {
        if (!isset(self::$colors)) {
            self::$colors = array();
        
            for ($r = 0; $r < 256; $r = $r+16) {
                for ($g = 0; $g < 256; $g = $g+16) {
                    for ($b = 0; $b < 256; $b = $b+16) {
                        self::$colors[] = new Color($r, $g, $b);
                    }
                }
            }
        }
        
        parent::__construct(self::$colors);
    }
}
