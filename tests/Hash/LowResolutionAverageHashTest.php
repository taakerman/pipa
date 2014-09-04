<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Hash;

use Taakerman\Pipa\Hash\Functions\LowResolutionAverageHash;

use Taakerman\Pipa\PipaUnitTest;

class LowResolutionAverageHashTest extends PipaUnitTest {
    public function testAlyson() {
        $image = $this->load('alyson.jpg');
        $this->assertNotNull($image);
        
        $imageHash = LowResolutionAverageHash::hash($image);
        $this->assertNotNull($imageHash);
        
        $hash = $imageHash->getHash();
        $this->assertEquals('ffffcf87abc3ffe0', $hash);
    }
}
