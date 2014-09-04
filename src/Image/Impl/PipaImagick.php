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

use Imagick;
use ImagickPixel;
use ImagickDraw;

/**
 * Bridges Pipa and Imagick library
 * Heavily inspired by the great Intervention/Image PHP library
 */
class PipaImagick implements Pipa {
    /* @var $resource \Imagick */
    private $resource;
    private $width;
    private $height;
    
    function __construct(Imagick $resource) {
        $this->resource = $resource;
        $this->width = $resource->getImageWidth();
        $this->height = $resource->getImageHeight();
    }

    public function getHeight() {
        return $this->height;
    }

    public static function imagick2pixel(ImagickPixel $pixel) {
        $r = (int) ($pixel->getColorValue(Imagick::COLOR_RED) * 255.0);
        $g = (int) ($pixel->getColorValue(Imagick::COLOR_GREEN) * 255.0);
        $b = (int) ($pixel->getColorValue(Imagick::COLOR_BLUE) * 255.0);
        $a = (int) ($pixel->getColorValue(Imagick::COLOR_ALPHA) * 255.0);
        
        return  ($a << 24) | 
                ($r << 16) | 
                ($g <<  8) | 
                ($b);
    }
    
    /**
     * Returns the pixel as an ImagickPixel
     * 
     * @param int $pixel
     * @return \ImagickPixel imagick pixel
     */
    public static function pixel2imagick($pixel) {
        $a = ($pixel >> 24) & 0xFF;
        $r = ($pixel >> 16) & 0xFF;
        $g = ($pixel >> 8) & 0xFF;
        $b = $pixel & 0xFF;
        $a = (float) ($a / 255.0);

        $ip = new ImagickPixel(
            sprintf('rgba(%d, %d, %d, %.2f)', $r, $g, $b, $a)
        );

        return $ip;
    }
    
    public function getPixel($x, $y) {
        return self::imagick2pixel($this->resource->getImagePixelColor($x, $y));
    }

    public function getWidth() {
        return $this->width;
    }

    public function setPixel($x, $y, $pixel) {
        $ip = self::pixel2imagick($pixel);
        
        $draw = new ImagickDraw();
        $draw->setFillColor($ip);
        $draw->point($x, $y);
        $this->resource->drawImage($draw);
    }
}
