<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Image\Impl;

use Taakerman\Pipa\Image\Pipa;

/**
 * Bridges Pipa image and GD library
 */
class PipaGD implements Pipa {
    // GD is completely opposite of other implementations
    // and is defined opposite of standard rgba color space
    // http://en.wikipedia.org/wiki/RGBA_color_space
    // http://www.w3.org/TR/css3-color/
    const OPAQUE = 0x00; //0xFF; // no transparency
    const TRANSPARENT = 0x7F; //0x00; // fully transparent
    
    private $resource;
    private $width;
    private $height;
    
    public function __construct($resource) {
        $this->resource = $resource;
        $this->width = imagesx($resource);
        $this->height = imagesy($resource);
    }

    public static function pixel2gd($pixel) {
        $a = ($pixel >> 24) & 0xFF;
        $r = ($pixel >> 16) & 0xFF;
        $g = ($pixel >> 8) & 0xFF;
        $b = $pixel & 0xFF;

        // faster version below
        /*
        // normalize from 0..255 to 0..127
        $coeff = 127.0 / 255.0;
        $a = $a * $coeff;
        
        // invert
        $a = 127.0 - $a;
        
        // round
        $a = (int) round($a);
        */
        
        // convert alpha channel
        $a = (int) ((127.0 - (($a / 255.0) * 127.0)) + 0.5);
        
        return ($a << 24) | 
                 ($r << 16) | 
                 ($g <<  8) | 
                 ($b);
    }
    
    public static function gd2pixel($gd) {
        $a = ($gd >> 24) & 0xFF;
        $r = ($gd >> 16) & 0xFF;
        $g = ($gd >> 8) & 0xFF;
        $b = $gd & 0xFF;
        
        // faster version below
        /*
        // invert
        $a = 127.0 - $a;
        
        // normalize from 0..127 to 0..255
        $coeff = 255.0 / 127.0;
        $a = $a * $coeff;
        
        // round
        $a = (int) round($a);
        */
        
        // convert alpha channel
        $a = (int) ((((127.0 - $a) / 127.0) * 255.0) + 0.5);

        return  ($a << 24) | 
                ($r << 16) | 
                ($g <<  8) | 
                ($b);
    }
    
    public function getHeight() {
        return $this->height;
    }

    public function getPixel($x, $y) {
        return $this->gd2pixel(imagecolorat($this->resource, $x, $y));
    }

    public function getWidth() {
        return $this->width;
    }

    public function setPixel($x, $y, $pixel) {
        imagesetpixel($this->resource, $x, $y, $this->pixel2gd($pixel));
    }

}