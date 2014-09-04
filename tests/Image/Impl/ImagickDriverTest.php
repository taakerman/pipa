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

use Taakerman\Pipa\PipaUnitTest;
use Taakerman\Pipa\Image\Impl\ImagickDriver;
use Taakerman\Pipa\Resources;
use Imagick;

class ImagickDriverTest extends PipaUnitTest {
    private $filename = 'tmp-driver-test-file.png';
    
    private $img = array(
            array(0xffff0000, 0xff00ff80, 0xff800080, 0xff800080, ),
            array(0xffffff00, 0xffff0000, 0xff00ff80, 0xff800080, ),
            array(0xff804000, 0xffffff00, 0xffff0000, 0xff00ff80, ),
            array(0xff804000, 0xff804000, 0xffffff00, 0xffff0000, ),
    );
    
    public function testIsEnabled() {
        $driver = new ImagickDriver();
        $this->assertTrue($driver->isEnabled());
    }
    
    public function testReadFile() {
        $driver = new ImagickDriver();
        $image = $driver->readFile(Resources::pathOf('nn-4x4.png'));
        $this->assertImageArrayEquals($this->img, $image);
    }
    
    public function testReadMemory() {
        $driver = new ImagickDriver();
        $image = $driver->readMemory(file_get_contents(Resources::pathOf('nn-4x4.png')));
        $this->assertImageArrayEquals($this->img, $image);
    }
    
    public function testWriteFile() {
        $driver = new ImagickDriver();
        $expected = $driver->readMemory(file_get_contents(Resources::pathOf('nn-4x4.png')));
        $result = $driver->writeFile($this->filename, $expected);
        $this->assertTrue($result, "Error writing file");
        
        // Note that this test differs from GD Driver test because imagick
        // is a bit volatile on image writes on my test system:
        // OP: Mac OS X 10.9.4 x64
        // PHP: 5.5.9
        // Imagick Module: 3.1.2 
        // Imagick: 6.8.9-1 Q16 2014-05-12 x86
        
        // it reports different color strings back every few invocations
        // sometimes srgb, sometimes srgba. 
        // when srgba is encountered, the alpha channel is very volatile
    }
    
    /*public function testImagick() {
        // just written by the testWriteFile() test
        $path = $this->filename;
        $resource = new \Imagick();

        try {
            $h = fopen($path, "rw+");
            $res = $resource->readImagefile($h);
            if (!$res) {
                throw new Exception("Imagick returned false for file '$path'");
            }
        } catch (\ImagickException $e) {
            if (!file_exists($path)) {
                throw new Exception("File could not be found '$path'");
            }
            
            throw new Exception("Unable to read image data from path '$path' with Imagick");
        }
        
        $width = $resource->getImageWidth();
        $height = $resource->getImageHeight();
        
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $pixel = $resource->getImagePixelColor($x, $y);
                $r = (int) ($pixel->getColorValue(Imagick::COLOR_RED) * 255.0);
                $g = (int) ($pixel->getColorValue(Imagick::COLOR_GREEN) * 255.0);
                $b = (int) ($pixel->getColorValue(Imagick::COLOR_BLUE) * 255.0);
                $a = intval($pixel->getColorValue(\Imagick::COLOR_ALPHA) * 255.0);
                
                // this varies between srgb and srgba
                // and the srgba values differ on each invocation
                echo $pixel->getcolorasstring() . "\n";
            }
        }
    }*/
}
