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

use Taakerman\Pipa\Filter\Normalizer;
use Taakerman\Pipa\Util\Normalization\SigmoidNormalization;

use Taakerman\Pipa\PipaUnitTest;

class NormalizerTest extends PipaUnitTest {
    public function testLinear() {
        $image = $this->load('alyson.jpg');
        $expected = $this->load('alyson-norm-linear.png');
        
        $normalized = Normalizer::normalize($image);
        $this->assertImagesEquals($expected, $normalized);
        //$this->save($normalized, 'tests/Filter/norm-linear.png');
    }
    
    public function testSigmoid() {
        $image = $this->load('alyson.jpg');
        $expected = $this->load('alyson-norm-sigmoid.png');
        
        $func = new SigmoidNormalization();
        $normalized = Normalizer::normalize($image, null, $func);
        $this->assertImagesEquals($expected, $normalized);
        //$this->save($normalized, 'tests/Filter/norm-sigmoid.png');
    }
}