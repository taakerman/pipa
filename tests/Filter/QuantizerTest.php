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

use Taakerman\Pipa\Filter\Quantizer;
use Taakerman\Pipa\Color\Palette\BinaryPalette;
use Taakerman\Pipa\Color\Palette\ReallyWebsafePalette;
use Taakerman\Pipa\Filter\Quantization\LeastSquaresQuantizer;
use Taakerman\Pipa\Filter\Quantization\PaletteDistanceQuantizer;
use Taakerman\Pipa\Util\Distance\ManhattanDistance;
use Taakerman\Pipa\Util\Distance\EuclideanDistance;
use Taakerman\Pipa\Analysis\ColorCount;
use Taakerman\Pipa\Analysis\ColorMap;
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Color\Palette\Palette;

use Taakerman\Pipa\PipaUnitTest;

class QuantizerTest extends PipaUnitTest {
    public function testBinary() {
        $image = $this->load('alyson.jpg');

        $palette = new BinaryPalette();
        $quantizer = new LeastSquaresQuantizer($palette);
        $quantized = Quantizer::quantize($image, null, $quantizer);
        
        $this->assertEquals(2, ColorCount::calculate($quantized));
        $colorMap = ColorMap::calculate($quantized);
        
        $this->assertTrue(isset($colorMap[0xFF000000]), 'No black color in color map');
        $this->assertTrue(isset($colorMap[0xFFFFFFFF]), 'No white color in color map');
    }
    
    public function testHTML4ColorNamesPalette() {
        $image = $this->load('alyson.jpg');

        $distance = new ManhattanDistance();
        $palette = new ReallyWebsafePalette();
        $quantizer = new PaletteDistanceQuantizer($distance, $palette);
        
        $quantized = Quantizer::quantize($image, null, $quantizer);
        
        //$this->save($quantized, 'tests/Filter/alyson-quantized-man-html4.png');
        $this->assertEquals(9, ColorCount::calculate($quantized));
    }

    public function testUserDefinedPalette() {
        $userDefinedColors = array(
            0xfc1ac91,0xffaa947c,0xffd1c1aa,0xffd5c5ae,0xff8e7a62,0xffcab69e,0xffbfa991,0xffbea890,0xffa58f77,0xffcbb79e,0xffcab69d,0xffc9b59c,
        );
        
        $palette = new Palette();
        foreach ($userDefinedColors as $color) {
            $palette->add(Color::fromInt($color));
        }
        
        $image = $this->load('alyson.jpg');

        $distance = new EuclideanDistance();
        $quantizer = new PaletteDistanceQuantizer($distance, $palette);
        
        $quantized = Quantizer::quantize($image, null, $quantizer);
        
        //$this->save($quantized, 'tests/Filter/alyson-quantized-user-def.png');
        $this->assertEquals(12, ColorCount::calculate($quantized));
        $this->assertEquals(count($userDefinedColors), ColorCount::calculate($quantized));
    }
}
