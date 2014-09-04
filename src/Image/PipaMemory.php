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

use Taakerman\Pipa\Image\Pipa;

/**
 * A Pipa image kept entirely in memory
 */
class PipaMemory implements Pipa {
    private $width;
    private $height;
    private $data;
    
    private function __construct(array $data = null) {
        $this->data = $data;
        $this->height = count($data);
        $this->width = count($data[0]);
    }
    
    public function getHeight() {
        return $this->height;
    }

    public function getPixel($x, $y) {
        return $this->data[$y][$x];
    }

    public function getWidth() {
        return $this->width;
    }

    public function setPixel($x, $y, $pixel) {
        $this->data[$y][$x] = $pixel;
    }

    public static function fromArray($data) {
        return new PipaMemory($data);
    }
    
    public static function fromSize($width, $height, $pixel = 0x00FFFFFF) {
        $data = array_fill(0, $height, array_fill(0, $width, $pixel));
        return new PipaMemory($data);
    }
    
    public static function fromImage(Pipa $image) {
        $width = $image->getWidth();
        $height = $image->getHeight();
        $data = array_fill(0, $height, array_fill(0, $width, 0));
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $data[$y][$x] = $value;
            }
        }
        
        return new PipaMemory($data);
    }
}
