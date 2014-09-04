<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util\Distance;

use Taakerman\Pipa\Util\Distance\ManhattanDistance;

use Taakerman\Pipa\PipaUnitTest;

class ManhattanDistanceTest extends PipaUnitTest {

    public function test1d() {
        $fn = new ManhattanDistance();
        $dist = $fn->cartesians(1, 3);
        $this->assertEquals(2, $dist);
        
        $dist = $fn->cartesians(-2, 1);
        $this->assertEquals(3, $dist);
        
        $dist = $fn->cartesians(1, -2);
        $this->assertEquals(3, $dist);
        
        $dist = $fn->cartesians(0.314, -6.034);
        $this->assertEquals(6.348, $dist, '', self::DELTA);
    }
    
    public function test2d() {
        $fn = new ManhattanDistance();
        $dist = $fn->cartesians(array(1,2), array(8,9));
        $this->assertEquals(14, $dist, '', self::DELTA);

        $dist = $fn->cartesians(array(-8, -9), array(-1,-2));
        $this->assertEquals(14, $dist, '', self::DELTA);
        
        $dist = $fn->cartesians(array(-1,-2), array(-8, -9));
        $this->assertEquals(14, $dist, '', self::DELTA);
        
        $dist = $fn->cartesians(array(-30.765,15.62093), array(3.004,-4.5));
        $this->assertEquals(53.88993, $dist, '', self::DELTA);
    }
    
    public function test3d() {
        $fn = new ManhattanDistance();
        $dist = $fn->cartesians(array(1,2,3), array(8,9,0));
        $this->assertEquals(17, $dist, '', self::DELTA);
        
        $dist = $fn->cartesians(array(-4.632,65.78,-105), array(-77,43.778,0.005));
        $this->assertEquals(199.375, $dist, '', self::DELTA);
    }
}
