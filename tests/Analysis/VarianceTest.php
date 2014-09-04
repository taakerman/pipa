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

use Taakerman\Pipa\Analysis\Mean;
use Taakerman\Pipa\Analysis\Variance;
use Taakerman\Pipa\PipaUnitTest;

class VarianceTest extends PipaUnitTest {
    public function testVariance() {
        $expected = array(1238.1503451699, 1780.0338330498, 1995.3611594501);
        
        $image = $this->load('alyson.jpg');
        $var = Variance::calculate($image);
        
        $this->assertEquals($expected, $var, null, PipaUnitTest::DELTA);
    }
    
    public function testEqualVariance() {
        $expected = array(1238.1503451699, 1780.0338330498, 1995.3611594501);
        $mean = array(186.577456, 156.496116, 127.716864);
        
        $image = $this->load('alyson.jpg');
        $var1 = Variance::calculate($image, $mean);

        $this->assertEquals($expected, $var1, null, PipaUnitTest::DELTA);
        
        $var2 = Variance::calculate($image);
        $this->assertEquals($expected, $var2, null, PipaUnitTest::DELTA);
    }
    
    public function testNotEqualVariance() {
        $mean = array(100, 200, 50);
        
        $image = $this->load('alyson.jpg');
        $var1 = Variance::calculate($image);
        $var2 = Variance::calculate($image, $mean); // different means

        $this->assertNotEquals($var1, $var2);
    }
}
