<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Filter;

use Taakerman\Pipa\Filter\Desaturater;

use Taakerman\Pipa\PipaUnitTest;

class DesaturaterTest extends PipaUnitTest {
    public function testDesaturation() {
        $image = $this->load('alyson.jpg');
        $expected = $this->load('alyson-desaturation-luminance.png');
        
        $desaturated = Desaturater::desaturate($image);
        $this->assertImagesEquals($expected, $desaturated);
        
        //$this->save($desaturated, 'tests/resources/files/alyson-desaturation-luminance.png');
    }
    
    public function testDifferentCoefficients() {
        $image = $this->load("alyson.jpg");
        $expected = $this->load('alyson-desaturation-luminance.png');
        
        $desaturated = Desaturater::desaturate($image, null, Desaturater::$SMPTE_C);
        $this->assertImagesNotEquals($expected, $desaturated);
    }
}
