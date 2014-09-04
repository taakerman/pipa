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
use Taakerman\Pipa\Image\Impl\PipaGD;

use Exception;

/**
 * GD Driver handles I/O functions using the GD library
 * Heavily inspired by the great Intervention/Image PHP library
 * 
 * @link http://image.intervention.io
 * @link https://github.com/Intervention/image
 */
class GDDriver implements Driver {
    public static function extensionLoaded() {
        return extension_loaded('gd') && function_exists('gd_info');
    }
    
    public function isEnabled() {
        return self::extensionLoaded();
    }
    
    public function readMemory($binary) {
        $resource = @imagecreatefromstring($binary);
        if ($resource === false) {
            throw new Exception('Unable to read image data with GD');
        }
        
        $width = imagesx($resource);
        $height = imagesy($resource);
        $data = array_fill(0, $height, array_fill(0, $width, 0xFF000000));
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $pixel = imagecolorat($resource, $x, $y);
                $data[$y][$x] = PipaGD::gd2pixel($pixel);
            }
        }
        
        return PipaMemory::fromArray($data);
    }
    
    public function readFile($path) {
        $info = @getimagesize($path);
        if ($info === false) {
            if (!file_exists($path)) {
                throw new Exception("File could not be found '$path'");
            }
            
            throw new Exception("Unable to read image data from path '$path' with GD");
        }
        
        switch ($info[2]) {
            case IMAGETYPE_PNG:
                return new PipaGD(imagecreatefrompng($path));
            case IMAGETYPE_JPEG:
                return new PipaGD(imagecreatefromjpeg($path));
            case IMAGETYPE_GIF:
                return new PipaGD(imagecreatefromgif($path));
            default:
                throw new Exception('Unable to read image data with GD, unsupported format ' . $info[2]);
        }
    }
    
    public function writeFile($path, Pipa $image, array $options = array()) {
        $ext = strtolower(substr(strrchr($path, '.'), 1));
        if (!in_array($ext, array('jpg', 'jpeg', 'png', 'gif'))) {
            throw new Exception('GD is only capable of storing jpg, gif or png files');
        }
        
        $resource = @imagecreatetruecolor($image->getWidth(), $image->getHeight());
        if ($resource === false) {
            throw new Exception('Unable to write image data with GD');
        }
        
        $w = $image->getWidth();
        $h = $image->getHeight();
        for ($x = 0; $x < $w; ++$x) {
            for ($y = 0; $y < $h; ++$y) {
                $value = $image->getPixel($x, $y);
                
                $pixel = PipaGD::pixel2gd($value);
                imagesetpixel($resource, $x, $y, $pixel);
            }
        }

        $result = false;
        switch ($ext) {
            case 'jpg':
            case 'jpeg':
                $result = imagejpeg($resource, $path);
                break;
            case 'gif':
                $result = imagegif($resource, $path);
                break;
            case 'png':
                $result = imagepng($resource, $path);
                break;
        }
        
        return $result;
    }
}
