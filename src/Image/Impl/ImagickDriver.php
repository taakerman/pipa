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

use Taakerman\Pipa\Image\Driver;
use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Image\Impl\PipaImagick;

use Imagick;
use ImagickPixel;
use ImagickDraw;
use ImagickException;

use Exception;

/**
 * Imagick Driver handles I/O functions using the Imagick library
 * Heavily inspired by the great Intervention/Image PHP library
 * 
 * Note that this driver is volatile on writes
 * OP: Mac OS X 10.9.4 x64
 * PHP: 5.5.9
 * Imagick Module: 3.1.2 
 * Imagick: 6.8.9-1 Q16 2014-05-12 x86
 *
 * it reports different color strings back every few invocations
 * sometimes srgb, sometimes srgba. 
 * when srgba is encountered, the alpha channel is very volatile
 * 
 * @link http://image.intervention.io
 * @link https://github.com/Intervention/image
 */
class ImagickDriver implements Driver {
    public static function extensionLoaded() {
        return extension_loaded('imagick') && class_exists("Imagick");
    }
    
    public function isEnabled() {
        return self::extensionLoaded();
    }
    
    public function readMemory($binary) {
        $resource = new Imagick();

        try {
            $resource->readImageBlob($binary);
        } catch (ImagickException $e) {
            throw new Exception('Unable to read image with Imagick');
        }
        
        $width = $resource->getImageWidth();
        $height = $resource->getImageHeight();
        
        $data = array_fill(0, $height, array_fill(0, $width, 0xFF000000));
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $ip = $resource->getImagePixelColor($x, $y);
                $data[$y][$x] = PipaImagick::imagick2pixel($ip);
            }
        }
        
        return PipaMemory::fromArray($data);
    }
    
    public function readFile($path) {
        $resource = new Imagick();

        try {
            $res = $resource->readImage($path);
            if (!$res) {
                throw new Exception("Imagick returned false for file '$path'");
            }
            return new PipaImagick($resource);
        } catch (ImagickException $e) {
            if (!file_exists($path)) {
                throw new Exception("File could not be found '$path'");
            }
            
            throw new Exception("Unable to read image data from path '$path' with Imagick");
        }
    }
    
    public function writeFile($path, Pipa $image, array $options = array()) {
        $w = $image->getWidth();
        $h = $image->getHeight();
        
        $resource = new Imagick();
        $resource->newImage($w, $h, new ImagickPixel('rgba(0,0,0,1.0)'), 'png');
        
        for ($x = 0; $x < $w; ++$x) {
            for ($y = 0; $y < $h; ++$y) {
                $pixel = $image->getPixel($x, $y);
        
                $ip = PipaImagick::pixel2imagick($pixel);
                
                $draw = new ImagickDraw();
                $draw->setFillColor($ip);
                $draw->point($x, $y);
                $resource->drawImage($draw);
            }
        }
        
        return $resource->writeimage($path);
    }
}
