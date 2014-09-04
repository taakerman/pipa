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

use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Image\Impl\PipaGD;
use Taakerman\Pipa\Util\StringBuilder;

use Taakerman\Pipa\PipaUnitTest;

class PipaGDTest extends PipaUnitTest {
    private function getColorTestSuite() {
        return array(
            // bottom
            array('pipa' => 0x00000000, 'gd' => 0x7F000000),
            array('pipa' => 0x01000000, 'gd' => 0x7F000000),
            array('pipa' => 0x02000000, 'gd' => 0x7E000000),
            array('pipa' => 0x03000000, 'gd' => 0x7E000000),
            array('pipa' => 0x04000000, 'gd' => 0x7D000000),
            array('pipa' => 0x05000000, 'gd' => 0x7D000000),

            // middle
            array('pipa' => 0x7C000000, 'gd' => 0x41000000),
            array('pipa' => 0x7D000000, 'gd' => 0x41000000),
            array('pipa' => 0x7E000000, 'gd' => 0x40000000),
            array('pipa' => 0x7F000000, 'gd' => 0x40000000),
            array('pipa' => 0x80000000, 'gd' => 0x3F000000),
            array('pipa' => 0x81000000, 'gd' => 0x3F000000),
            array('pipa' => 0x82000000, 'gd' => 0x3E000000),
            array('pipa' => 0x83000000, 'gd' => 0x3E000000),

            // top
            array('pipa' => 0xFA000000, 'gd' => 0x02000000),
            array('pipa' => 0xFB000000, 'gd' => 0x02000000),
            array('pipa' => 0xFC000000, 'gd' => 0x01000000),
            array('pipa' => 0xFD000000, 'gd' => 0x01000000),
            array('pipa' => 0xFE000000, 'gd' => 0x00000000),
            array('pipa' => 0xFF000000, 'gd' => 0x00000000),
        );
    }
    
    public function testPixel2Gd() {
        $arr = $this->getColorTestSuite();

        foreach ($arr as $color) {
            $pipa = Color::fromInt($color['pipa']);
            $gd = PipaGD::gd2pixel($color['gd']);
            
            list($er,$eg,$eb,$ea) = $pipa->toRgba();
            list($ar,$ag,$ab,$aa) = Color::fromInt($gd)->toRgba();
            
            $this->assertEquals($er, $ar, 'Red channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eg, $ag, 'Blue channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eb, $ab, 'Green channel differ for #' . $pipa->toHexa());
            
            // allow alpha to differ by 1 because of normalization from 127 -> 255
            $this->assertEquals($ea, $aa, 'Alpha channel differ for #' . $pipa->toHexa(), 1);
        }
    }
    
    public function testGd2Pixel() {
        $arr = $this->getColorTestSuite();

        foreach ($arr as $color) {
            $pipa = Color::fromInt($color['pipa']);
            $gd = PipaGD::gd2pixel($color['gd']);
            
            list($er, $eg, $eb, $ea) = $pipa->toRgba();
            list($ar, $ag, $ab, $aa) = Color::fromInt($gd)->toRgba();
            
            $this->assertEquals($er, $ar, 'Red channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eg, $ag, 'Blue channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eb, $ab, 'Green channel differ for #' . $pipa->toHexa());
            
            // allow alpha to differ by 1 because of normalization from 255 -> 127
            $this->assertEquals($ea, $aa, 'Alpha channel differ for #' . $pipa->toHexa(), 1);
        }
    }
    
    private function createPhpArray($int) {
        $c = Color::fromInt($int);
        
        //echo "PHP array: ";
        $sb = new StringBuilder();
        $sb->append('array(');
        $sb->append("'pipa' => 0x" . str_pad(dechex($c->toInt()), 8, '0', STR_PAD_LEFT));
        $sb->append(',');
        $sb->append("'gd' => 0x" . str_pad(dechex(PipaGD::pixel2gd($c->toInt())), 8, '0', STR_PAD_LEFT));
        $sb->append(',');
        
        $sb->append('),');
        
        print $sb->build() . "\n";
    }
}
