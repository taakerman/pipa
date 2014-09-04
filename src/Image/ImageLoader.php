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

use Taakerman\Pipa\Image\Impl\GDDriver;
use Taakerman\Pipa\Image\Impl\ImagickDriver;

use Exception;

/**
 * The loader is a frontend for drivers, used to load images
 */
class ImageLoader {
    private $path = null;
    private $memory = false;
    private $driver = null;
    
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
    
    public static function loader() {
        return new ImageLoader();
    }
    
    public function path($path) {
        $this->path = $path;
        return $this;
    }

    public function memory() {
        $this->memory = true;
        return $this;
    }
    
    public function load() {
        if ($this->memory) {
            // read file to memory for faster processing
            $binary = file_get_contents($this->path);
            return $this->driver->readMemory($binary);
        } else {
            return $this->driver->readFile($this->path);            
        }
    }
}