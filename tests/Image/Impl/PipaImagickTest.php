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
use Taakerman\Pipa\Image\Impl\PipaImagick;
use Taakerman\Pipa\Util\StringBuilder;

use Taakerman\Pipa\PipaUnitTest;

use Imagick;
use ImagickPixel;

class PipaImagickTest extends PipaUnitTest {

    private function getColorTestSuite() {
        return array(
            // bottom
            array('pipa' => 0x00000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.00)'),),
            array('pipa' => 0x01000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.00)'),),
            array('pipa' => 0x02000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.01)'),),
            array('pipa' => 0x03000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.01)'),),
            array('pipa' => 0x04000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.02)'),),
            array('pipa' => 0x05000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.02)'),),

            // middle
            array('pipa' => 0x7c000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.49)'),),
            array('pipa' => 0x7d000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.49)'),),
            array('pipa' => 0x7e000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.49)'),),
            array('pipa' => 0x7f000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.50)'),),
            array('pipa' => 0x80000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.50)'),),
            array('pipa' => 0x81000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.51)'),),
            array('pipa' => 0x82000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.51)'),),
            array('pipa' => 0x83000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.51)'),),

            // top
            array('pipa' => 0xfa000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.98)'),),
            array('pipa' => 0xfb000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.98)'),),
            array('pipa' => 0xfc000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.99)'),),
            array('pipa' => 0xfd000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 0.99)'),),
            array('pipa' => 0xfe000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 1.00)'),),
            array('pipa' => 0xff000000,'imagick' => new ImagickPixel('rgba(0, 0, 0, 1.00)'),),
        );
    }
    
    public function testPixel2Imagick() {
        $arr = $this->getColorTestSuite();

        foreach ($arr as $color) {
            $pipa = Color::fromInt($color['pipa']);
            /* @var $ip \ImagickPixel */
            $imagick = PipaImagick::pixel2imagick($pipa->toInt());
            
            list($er,$eg,$eb,$ea) = $pipa->toRgba();
            $ar = $imagick->getColorValue(Imagick::COLOR_RED);
            $ag = $imagick->getColorValue(Imagick::COLOR_GREEN);
            $ab = $imagick->getColorValue(Imagick::COLOR_BLUE);
            $aa = $imagick->getColorValue(Imagick::COLOR_ALPHA);
            
            $this->assertEquals($er, $ar, 'Red channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eg, $ag, 'Green channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eb, $ab, 'Blue channel differ for #' . $pipa->toHexa());
            
            // allow alpha to differ by 0.02 because of normalization from 255 to 1
            $this->assertEquals($ea/255.0, $aa, 'Alpha channels differ for #' . $pipa->toHexa(), 0.02);
        }
    }
    
    public function testImagick2Pixel() {
        $arr = $this->getColorTestSuite();

        foreach ($arr as $color) {
            $pipa = Color::fromInt($color['pipa']);
            $imagick = PipaImagick::imagick2pixel($color['imagick']);
            
            list($er, $eg, $eb, $ea) = $pipa->toRgba();
            list($ar, $ag, $ab, $aa) = Color::fromInt($imagick)->toRgba();
            
            $this->assertEquals($er, $ar, 'Red channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eg, $ag, 'Blue channel differ for #' . $pipa->toHexa());
            $this->assertEquals($eb, $ab, 'Green channel differ for #' . $pipa->toHexa());
            
            // allow alpha to differ by 2 because of normalization from 1 -> 255
            $this->assertEquals($ea, $aa, 'Alpha channel differ for #' . $pipa->toHexa(), 2);
        }
    }
    
    private function createPhpArray($int) {
        $c = Color::fromInt($int);
        list($r, $g, $b, $a) = $c->toRgba();
        $a = (float) ($a / 255.0);
        
        //echo "PHP array: ";
        $sb = new StringBuilder();
        $sb->append('array(');
        $sb->append("'pipa' => 0x" . str_pad(dechex($c->toInt()), 8, '0', STR_PAD_LEFT));
        $sb->append(',');
        $sb->appendf("'imagick' => new ImagickPixel('rgba(%d, %d, %d, %.2f)')", $r, $g, $b, $a);
        $sb->append(',');
        
        $sb->append('),');
        
        print $sb->build() . "\n";
    }
}
