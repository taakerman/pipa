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
use Taakerman\Pipa\Image\Impl\GDDriver;
use Taakerman\Pipa\Image\Impl\ImagickDriver;

use Exception;

/**
 * ImageWriter is a frontend for Driver, used to write images
 */
class ImageWriter {
    /* @var $driver \Taakerman\Pipa\Image\Driver */
    private $driver = null;
    private $path = null;
    private $options = array();
    
    private function __construct() {
        // prefer GD driver
        if (GDDriver::extensionLoaded()) {
            $this->driver = new GDDriver();
        } else if (ImagickDriver::extensionLoaded ()) {
            $this->driver = new ImagickDriver();
        } else {
            throw new Exception('No image drivers could be found, do you have GD or Imagick installed?');
        }
    }
    
    public static function writer() {
        return new ImageWriter();
    }
    
    public function path($path) {
        $this->path = $path;
        return $this;
    }
    
    public function options(array $options = array()) {
        $this->options = $options;
        return $this;
    }
    
    public function write(Pipa $image) {
        $this->driver->writeFile($this->path, $image, $this->options);
    }
}