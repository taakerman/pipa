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
use Taakerman\Pipa\PipaUnitTest;

class MeanTest extends PipaUnitTest {
    public function testMean() {
        $expected = array(186.577456, 156.496116, 127.716864);
        
        $image = $this->load('alyson.jpg');
        $mean = Mean::calculate($image);

        $this->assertEquals($expected, $mean, null, PipaUnitTest::DELTA);
    }
}
