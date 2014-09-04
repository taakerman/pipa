<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Analysis;

use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Analysis\BestFit;

use Taakerman\Pipa\PipaUnitTest;

class BestFitTest extends PipaUnitTest {
    public function testSmallerImage() {
        // image
        $image = PipaMemory::fromSize(30, 40);
        
        // both smaller
        list($w, $h, $r) = BestFit::calculate($image, 60, 60, true);
        $this->assertEquals(45, $w);
        $this->assertEquals(60, $h);
        
        list($w, $h, $r) = BestFit::calculate($image, 60, 60, false);
        $this->assertEquals(30, $w);
        $this->assertEquals(40, $h);
        
        // height smaller
        list($w, $h, $r) = BestFit::calculate($image, 60, 20);
        $this->assertEquals(15, $w);
        $this->assertEquals(20, $h);
        
        // width smaller
        list($w, $h, $r) = BestFit::calculate($image, 20, 60);
        $this->assertEquals(20, $w);
        $this->assertEquals(27, $h);
    }
    
    public function testLargerImage() {
        // image
        $image = PipaMemory::fromSize(30, 40);
        
        // both larger
        list($w, $h, $r) = BestFit::calculate($image, 20, 20);
        $this->assertEquals(15, $w);
        $this->assertEquals(20, $h);
        
        // height larger
        list($w, $h, $r) = BestFit::calculate($image, 20, 60);
        $this->assertEquals(20, $w);
        $this->assertEquals(27, $h);
        
        // width larger
        list($w, $h, $r) = BestFit::calculate($image, 60, 20);
        $this->assertEquals(15, $w);
        $this->assertEquals(20, $h);
    }
    
    public function testExactImage() {
        // image
        $image = PipaMemory::fromSize(30, 40);
        
        // both exact
        list($w, $h, $r) = BestFit::calculate($image, 30, 40);
        $this->assertEquals(30, $w);
        $this->assertEquals(40, $h);
        
        // height exact, width smaller
        list($w, $h, $r) = BestFit::calculate($image, 20, 40);
        $this->assertEquals(20, $w);
        $this->assertEquals(27, $h);
        
        // height exact, width larger
        list($w, $h, $r) = BestFit::calculate($image, 60, 40);
        $this->assertEquals(30, $w);
        $this->assertEquals(40, $h);
        
        // width exact, height smaller
        list($w, $h, $r) = BestFit::calculate($image, 30, 20);
        $this->assertEquals(15, $w);
        $this->assertEquals(20, $h);
        
        // width exact, height larger
        list($w, $h, $r) = BestFit::calculate($image, 30, 60);
        $this->assertEquals(30, $w);
        $this->assertEquals(40, $h);
    }
}
