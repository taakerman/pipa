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
 * A Really Websafe Palette as described on wikipedia 2014  
 * {@link http://en.wikipedia.org/wiki/Web_colors}
 */
class ReallyWebsafePalette extends Palette {
    private static $colors;
    
    public function __construct() {
        if (!isset(self::$colors)) {
            self::$colors = array(
                Color::fromInt(0xFFFFFFFF), 
                Color::fromInt(0xFF00FFCC), 
                Color::fromInt(0xFF33FFCC),
                Color::fromInt(0xFF00FFFF),
                Color::fromInt(0xFF33FFFF),
                Color::fromInt(0xFF66FFFF),
                Color::fromInt(0xFF00FF66),
                Color::fromInt(0xFF33FF66),
                Color::fromInt(0xFF33FF33),
                Color::fromInt(0xFF66FF00),
                Color::fromInt(0xFF66FF33),
                Color::fromInt(0xFF00FF00),
                Color::fromInt(0xFFCCFF66),
                Color::fromInt(0xFFFFFF66),
                Color::fromInt(0xFFFFFF33),
                Color::fromInt(0xFFFFFF00),
                Color::fromInt(0xFFFF00FF),
                Color::fromInt(0xFFFF0000),
                Color::fromInt(0xFFFF0033),
                Color::fromInt(0xFF0000FF),
                Color::fromInt(0xFF000033), 
                Color::fromInt(0xFF000000)
            );
        }
        
        parent::__construct(self::$colors);
    }
}
