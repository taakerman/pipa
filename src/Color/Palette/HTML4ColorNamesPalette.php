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
 * A Palette of HTML4.1 Color Names
 * {@link http://en.wikipedia.org/wiki/X11_color_names}
 */
class HTML4ColorNamesPalette extends Palette {
    private static $colors;
    
    public function __construct() {
        // static instantiate colors
        if (!isset(self::$colors)) {
            self::$colors = array(
                'white' =>   Color::fromInt(0xFFFFFFFF),
                'silver' =>  Color::fromInt(0xFFC0C0C0),
                'gray' =>    Color::fromInt(0xFF808080),
                'black' =>   Color::fromInt(0xFF000000),
                'red' =>     Color::fromInt(0xFFFF0000),
                'maroon' =>  Color::fromInt(0xFF800000),
                'yellow' =>  Color::fromInt(0xFFFFFF00),
                'olive' =>   Color::fromInt(0xFF808000),
                'lime' =>    Color::fromInt(0xFF00FF00),
                'green' =>   Color::fromInt(0xFF008000),
                'aqua' =>    Color::fromInt(0xFF00FFFF),
                'teal' =>    Color::fromInt(0xFF008080),
                'blue' =>    Color::fromInt(0xFF0000FF),
                'navy' =>    Color::fromInt(0xFF000080),
                'fuchsia' => Color::fromInt(0xFFFF00FF),
                'purple' =>  Color::fromInt(0xFF800080)
            );
        }
        
        parent::__construct(self::$colors);
    }
    
    /**
     * Add the orange color to the palette as specified in CSS 2.1
     * 
     * @return \Taakerman\Pipa\Color\Palette\HTML4ColorNamesPalette
     */
    public function withOrange() {
        $this->add(Color::fromInt(0xFFFFA500));
        return $this;
    }
}
