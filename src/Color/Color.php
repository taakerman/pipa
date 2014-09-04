<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Color;

/**
 * The color class represents a color in different formats
 * and can be used for conversion between formats
 */
class Color {
    // http://en.wikipedia.org/wiki/RGBA_color_space
    // http://www.w3.org/TR/css3-color/
    const TRANSPARENT = 0x00; // fully transparent
    const OPAQUE = 0xFF; // no transparency
    
    /**
     * @var int $r red channel in the range [0..255]
     */
    public $r;
    
    /**
     * @var int $g green channel in the range [0..255]
     */
    public $g;
    
    /**
     * @var int $b blue channel in the range [0..255]
     */
    public $b;
    
    /**
     * @var int $a alpha channel in the range [0..255]
     */
    public $a;
    
    /**
     * Constructs a new color in RGBA color space
     * 
     * @param type $r the red in the range [0..255]
     * @param type $g the green in the range [0..255]
     * @param type $b the blue in the range [0..255]
     * @param type $a the alpha in the range [0..255]
     */
    public function __construct($r, $g, $b, $a = self::OPAQUE) {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
        $this->a = $a;
    }

    /**
     * Creates a color from an integer, used in Pipa images 
     * 
     * @param int $value the value
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromInt($value) {
        $a = ($value >> 24) & 0xFF;
        $r = ($value >> 16) & 0xFF;
        $g = ($value >> 8) & 0xFF;
        $b = $value & 0xFF;
        
        return new Color($r, $g, $b, $a);
    }
    
    /**
     * Converts this color to an integer, used in Pipa images
     * 
     * @return int
     */
    public function toInt() {
        return  ($this->a << 24) | 
                ($this->r << 16) | 
                ($this->g <<  8) | 
                ($this->b);
    }
    
    /**
     * Creates a color from RGBA color space
     * 
     * Red [0..255]
     * Green [0..255]
     * Blue [0..255]
     * Alpha [0..255] where 0 is transparent and 255 is opaque
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @param array $rgba the rgba values
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromRgba(array $rgba) {
        return new Color($rgba[0], $rgba[1], $rgba[2], $rgba[3]);
    }
    
    /**
     * Converts this color to RGBA color space
     * 
     * Red [0..255]
     * Green [0..255]
     * Blue [0..255]
     * Alpha [0..100%] where 0% is transparent and 100% is opaque
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @return array the rgba values
     */
    public function toRgba() {
        return array($this->r, $this->g, $this->b, $this->a);
    }
    
    /**
     * Creates a color from RGB color space
     * 
     * Red [0..255]
     * Green [0..255]
     * Blue [0..255]
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @param array $rgb
     * @return \Taakerman\Pipa\Color\Color
     */
    public static function fromRgb(array $rgb) {
        return new Color($rgb[0], $rgb[1], $rgb[2]);
    }
    
    /**
     * Converts this color to RGB color space
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @return array the rgb values
     */
    public function toRgb() {
        return array($this->r, $this->g, $this->b);
    }
    
    /**
     * Creates a color from HSV color space
     * 
     * Hue [0..360]
     * Saturation [0..1]
     * Value [0..1]
     * 
     * @link http://en.wikipedia.org/wiki/HSL_and_HSV
     * 
     * @param array $hsv the hsv values
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromHsv(array $hsv) {
        // follows directly from the formulae presented at wikipedia 2014 
        // http://en.wikipedia.org/wiki/HSL_and_HSV
        list($h, $s, $v) = $hsv;
        
        // H undefined
        if ($h < 0) {
            return Color::fromRgb(0, 0, 0);
        }
        
        $chroma = $v * $s;
        $hPrime = $h / 60;

        $x = $chroma * (1 - abs((fmod($hPrime, 2)) - 1));
        
        $rgb1 = array(0, 0, 0);
        if (0 <= $hPrime && $hPrime < 1) {
            $rgb1 = array($chroma, $x, 0);
        } else if (1 <= $hPrime && $hPrime < 2) {
            $rgb1 = array($x, $chroma, 0);
        } else if (2 <= $hPrime && $hPrime < 3) {
            $rgb1 = array(0, $chroma, $x);
        } else if (3 <= $hPrime && $hPrime < 4) {
            $rgb1 = array(0, $x, $chroma);
        } else if (4 <= $hPrime && $hPrime < 5) {
            $rgb1 = array($x, 0, $chroma);
        } else if (5 <= $hPrime && $hPrime < 6) {
            $rgb1 = array($chroma, 0, $x);
        }
        
        $m = $v - $chroma;
        
        $r = self::adjustRgb(($rgb1[0] + $m) * 255.0);
        $g = self::adjustRgb(($rgb1[1] + $m) * 255.0);
        $b = self::adjustRgb(($rgb1[2] + $m) * 255.0);
        
        return new Color($r, $g, $b);
    }
    
    /**
     * Converts this color to HSV color space
     * 
     * Hue [0..360]
     * Saturation [0..1]
     * Value [0..1]
     * 
     * @return array the hsv values
     */
    public function toHsv() {
        // follows directly from the formulae presented at wikipedia 2014 
        // http://en.wikipedia.org/wiki/HSL_and_HSV
        $h = $s = $v = 0;
        
        $rgb = $this->toRgb();
        list($r, $g, $b) = $rgb;
        
	$max = max($rgb);
	$min = min($rgb);
        
        // V
	$v = (float) $max;
        
	$chroma = (float) $max - $min;
        if ($chroma === 0.0) {
            // H undefined (in other words r=g=b=0 => black)
            return array($h, $s, $v / 255.0);
        }
        
        $hPrime = 0.0;
        switch ($max) {
            case $r:
                $hPrime = fmod((($g - $b) / $chroma), 6.0);
                break;
            case $g:
                $hPrime = (($b - $r) / $chroma) + 2.0;
                break;
            case $b:
                $hPrime = (($r - $g) / $chroma) + 4.0;
                break;
        }
        
        // H
        $h = $hPrime * 60.0;
        $h = $h < 0.0 ? $h + 360.0 : $h;
        
        // S
        $s = $chroma / $v;

        return array($h, $s, $v / 255.0);
    }
    
    /**
     * Creates a color from HSL (HSB) color space
     * 
     * Hue [0..360]
     * Saturation [0..1]
     * Lightness/Brightness [0..1]
     * 
     * @link http://en.wikipedia.org/wiki/HSL_and_HSV
     * 
     * @param array $hsl the hsl (hsb) values
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromHsl(array $hsl) {
        // follows directly from the formulae presented at wikipedia 2014 
        // http://en.wikipedia.org/wiki/HSL_and_HSV
        list($h, $s, $l) = $hsl;
        
        // H is undefined
        if ($h < 0) {
            return new Color(0, 0, 0);
        } 
        
        $chroma = (1.0 - abs((2.0 * $l) - 1.0)) * $s;
        $hPrime = $h / 60.0;

        $x = $chroma * (1.0 - abs(fmod($hPrime, 2.0) - 1.0));
        
        $rgb1 = array(0, 0, 0);
        if (0 <= $hPrime && $hPrime < 1) {
            $rgb1 = array($chroma, $x, 0);
        } else if (1 <= $hPrime && $hPrime < 2) {
            $rgb1 = array($x, $chroma, 0);
        } else if (2 <= $hPrime && $hPrime < 3) {
            $rgb1 = array(0, $chroma, $x);
        } else if (3 <= $hPrime && $hPrime < 4) {
            $rgb1 = array(0, $x, $chroma);
        } else if (4 <= $hPrime && $hPrime < 5) {
            $rgb1 = array($x, 0, $chroma);
        } else if (5 <= $hPrime && $hPrime < 6) {
            $rgb1 = array($chroma, 0, $x);
        }
        
        $m = $l - (0.5 * $chroma);
        $r = self::adjustRgb(($rgb1[0] + $m) * 255.0);
        $g = self::adjustRgb(($rgb1[1] + $m) *255.0);
        $b = self::adjustRgb(($rgb1[2] + $m) * 255.0);
        
        return new Color($r, $g, $b);
    }
    
    /**
     * Converts this color to HSL (HSB) color space
     * 
     * Hue [0..360]
     * Saturation [0..1]
     * Lightness/Brightness [0..1]
     * 
     * @link http://en.wikipedia.org/wiki/HSL_and_HSV
     * 
     * @return array the hsl (hsb) values
     */
    public function toHsl() {
        // follows directly from the formulae presented at wikipedia 2014 
        // http://en.wikipedia.org/wiki/HSL_and_HSV
	$rgb = $this->toRgb();
        list($r, $g, $b) = $rgb;
        
        $h = $s = $l = 0;
        
	$max = max($rgb);
	$min = min($rgb);
        
        // L
	$l = 0.5 * ($max/255.0 + $min/255.0);
        
	$chroma = (float) $max - $min;
        if ($chroma === 0.0) {
            // H undefined (in other words r=g=b=0 => black)
            return array($h, $s, $l);
        }
        
        $hPrime = 0;
        switch ($max) {
            case $r:
                $hPrime = fmod(($g - $b) / $chroma, 6.0);
                break;
            case $g:
                $hPrime = (($b - $r) / $chroma) + 2.0;
                break;
            case $b:
                $hPrime = (($r - $g) / $chroma) + 4.0;
                break;
        }
        
        // H
        $h = $hPrime * 60.0;
        $h = $h < 0.0 ? $h + 360.0 : $h;
        
        // S
        $s = ($chroma/255.0) / (1.0 - abs((2.0 * $l) - 1.0) );
        
        return array($h, $s, $l);
    }
    
    /**
     * Creates a color from RGB color space in Hexadecimal notation.
     * The hexadecimal value can be prefixed with '#'.
     * If the hexadecimal value consists of only 3 chars, then it will be 
     * expanded by doubling the chars
     * 
     * (#)RRGGBB
     * RR (Red) [0..255]
     * GG (Green) [0..255]
     * BB (Blue) [0..255]
     * 
     * Example of expansion
     * #f5a => ff55aa
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @param string $hex the hexadecimal value
     * @return \Taakerman\Pipa\Color\Color
     */
    public static function fromHex($hex) {
        if ($hex{0} === '#') {
            $hex = substr($hex, 1);
        }
        
        // expansion
        if (strlen($hex) === 3) {
            $r = $hex{0} . $hex{0};
            $g = $hex{1} . $hex{1};
            $b = $hex{2} . $hex{2};
        } else {
            $r = substr($hex, 0, 2);
            $g = substr($hex, 2, 2);
            $b = substr($hex, 4, 2);
        }
        
        return new Color(
                hexdec($r), 
                hexdec($g), 
                hexdec($b)
        );
    }
    
    /**
     * Converts this color to RGBA color space in hexadecimal notation
     * 
     * RRGGBB
     * RR (Red) [0..255]
     * GG (Green) [0..255]
     * BB (Blue) [0..255]
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @return string the hexadecimal value
     */
    public function toHex() {
        return sprintf('%02X%02X%02X', $this->r, $this->g, $this->b);
    }
    
    /**
     * Creates a color from RGBA color space in hexadecimal notation.
     * The hexadecimal value can be prefixed with '#'.
     * If the hexadecimal value consists of only 4 chars, then it will be 
     * expanded by doubling the chars
     * 
     * AARRGGBB
     * AA (Alpha) [0..255]
     * RR (Red) [0..255]
     * GG (Green) [0..255]
     * BB (Blue) [0..255]
     * 
     * Example of expansion
     * #ff5a => ffff55aa
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @param string $hexa the hexadecimal value
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromHexa($hexa) {
        if ($hexa{0} === '#') {
            $hexa = substr($hexa, 1);
        }
        
        // expansion
        if (strlen($hexa) === 4) {
            $a = $hexa{0} . $hexa{0};
            $r = $hexa{1} . $hexa{1};
            $g = $hexa{2} . $hexa{2};
            $b = $hexa{3} . $hexa{3};
        } else {
            $a = substr($hexa, 0, 2);
            $r = substr($hexa, 2, 2);
            $g = substr($hexa, 4, 2);
            $b = substr($hexa, 6, 2);
        }
        
        return new Color(
                hexdec($r), 
                hexdec($g), 
                hexdec($b), 
                hexdec($a)
        );
    }
    
    /**
     * Converts this color to RGBA in hexadecimal notation
     * 
     * AARRGGBB
     * AA (Alpha) [0..255]
     * RR (Red) [0..255]
     * GG (Green) [0..255]
     * BB (Blue) [0..255]
     * 
     * @link http://en.wikipedia.org/wiki/RGBA_color_space
     * 
     * @return string the hexadecimal value
     */
    public function toHexa() {
        return sprintf('%02X%02X%02X%02X', $this->a, $this->r, $this->g, $this->b);
    }
    
    /**
     * Creates a color where all channels have the same value, except alpha.
     * Alpha defaults to opaque
     * 
     * @param int $c the channel color in the range [0..255]
     * @param int $alpha the alpha channel in the range [0..255]
     * @return \Taakerman\Pipa\Color\Color the color
     */
    public static function fromChannel($c, $alpha = self::OPAQUE) {
        return new Color($c, $c, $c, $alpha);
    }
    
    /**
     * Desaturates a color with the given coefficients
     * 
     * @param array $coefficients
     * @return int a channel color in the range [0..255]
     */
    public function desaturate(array $coefficients) {        
        $cLinear = $this->r * $coefficients[0] + 
                $this->g * $coefficients[1] + 
                $this->b * $coefficients[2];
        
        return self::adjustRgb($cLinear);
    }
    
    /**
     * Adjusts a value to be in the range [0..255]
     * The $n will be rounded before testing against the range
     * and afterwards casted to an integer
     * 
     * @param mixed $n an integer or a float
     * @return int the adjusted value rounded and casted to an int
     */
    public static function adjustRgb($n) {
        return (int) self::adjust(round($n), 0, 255);
    }
    
    /**
     * Adjusts a number $n to be in the range [$min..$max]
     * 
     * @param mixed $n the number to be adjusted
     * @param mixed $min the minimum
     * @param mixed $max the maximum
     * @return mixed adjusted number
     */
    public static function adjust($n, $min, $max) {
        $n = $n < $min ? $min : $n;
        $n = $n > $max ? $max : $n;
        return $n;
    }
}
