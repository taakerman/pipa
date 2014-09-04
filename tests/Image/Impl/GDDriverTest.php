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
use Taakerman\Pipa\Image\Impl\GDDriver;
use Taakerman\Pipa\Resources;

class GDDriverTest extends PipaUnitTest {
    private $filename = 'tmp-driver-test-file.png';
    
    private $img = array(
            array(0xffff0000, 0xff00ff80, 0xff800080, 0xff800080, ),
            array(0xffffff00, 0xffff0000, 0xff00ff80, 0xff800080, ),
            array(0xff804000, 0xffffff00, 0xffff0000, 0xff00ff80, ),
            array(0xff804000, 0xff804000, 0xffffff00, 0xffff0000, ),
    );
    
    public function testIsEnabled() {
        $driver = new GDDriver();
        $this->assertTrue($driver->isEnabled());
    }
    
    public function testReadFile() {
        $driver = new GDDriver();
        $image = $driver->readFile(Resources::pathOf('nn-4x4.png'));
        $this->assertImageArrayEquals($this->img, $image);
    }
    
    public function testReadMemory() {
        $driver = new GDDriver();
        $image = $driver->readMemory(file_get_contents(Resources::pathOf('nn-4x4.png')));
        $this->assertImageArrayEquals($this->img, $image);
    }
    
    public function testWriteFile() {
        $driver = new GDDriver();
        $expected = $driver->readMemory(file_get_contents(Resources::pathOf('nn-4x4.png')));
        $result = $driver->writeFile($this->filename, $expected);
        $this->assertTrue($result, "Error writing file");
        
        $actual = $driver->readFile($this->filename);
        $this->assertImagesEquals($expected, $actual);
    }
}
