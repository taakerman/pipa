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

use Taakerman\Pipa\Analysis\ColorMap;
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\PipaUnitTest;

class ColorMapTest extends PipaUnitTest {
    private $image = array(
            array(0xff7f7f7f, 0xffdedede, 0xff464646, 0xff464646, ),
            array(0xfff6f6f6, 0xff7f7f7f, 0xffdedede, 0xff464646, ),
            array(0xff515151, 0xfff6f6f6, 0xff7f7f7f, 0xffdedede, ),
            array(0xff515151, 0xff515151, 0xfff6f6f6, 0xff7f7f7f, ),
    );
    
    public function testColorMap() {
        $image = PipaMemory::fromArray($this->image);
        $colorMap = ColorMap::calculate($image);

        $this->assertEquals(16, $image->getHeight() * $image->getWidth());
        $this->assertEquals(16, array_sum($colorMap));
        
        $threes = array(0xfff6f6f6, 0xff515151, 0xff464646);
        foreach ($threes as $three) {
            $hexa = Color::fromInt($three)->toHexa();
            $this->assertEquals(3, $colorMap[$three], "The color $hexa does not have frequency 3");
        }
        
        $this->assertEquals(4, $colorMap[0xff7f7f7f], "The color ff7f7f7f does not have frequency 4");
    }
}
