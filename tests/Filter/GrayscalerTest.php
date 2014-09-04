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

use Taakerman\Pipa\Filter\Grayscaler;
use Taakerman\Pipa\Util\Stopwatch;

use Taakerman\Pipa\PipaUnitTest;

class GrayscaleTest extends PipaUnitTest {
    private $grayscale = array(
            array(0xff7f7f7f, 0xffdedede, 0xff464646, 0xff464646, ),
            array(0xfff6f6f6, 0xff7f7f7f, 0xffdedede, 0xff464646, ),
            array(0xff515151, 0xfff6f6f6, 0xff7f7f7f, 0xffdedede, ),
            array(0xff515151, 0xff515151, 0xfff6f6f6, 0xff7f7f7f, ),
    );
    
    private $grayscalef = array(
            array(0xff7f7f7f, 0xffdedede, 0xff464646, 0xff464646, ),
            array(0xfff6f6f6, 0xff7f7f7f, 0xffdedede, 0xff464646, ),
            array(0xff515151, 0xfff6f6f6, 0xff7f7f7f, 0xffdedede, ),
            array(0xff515151, 0xff515151, 0xfff6f6f6, 0xff7f7f7f, ),
    );
    
    private $average = array(
            array(0xff555555, 0xff7f7f7f, 0xff555555, 0xff555555, ),
            array(0xffaaaaaa, 0xff555555, 0xff7f7f7f, 0xff555555, ),
            array(0xff404040, 0xffaaaaaa, 0xff555555, 0xff7f7f7f, ),
            array(0xff404040, 0xff404040, 0xffaaaaaa, 0xff555555, ),
    );
    
    private $lightness = array(
            array(0xff7f7f7f, 0xff7f7f7f, 0xff404040, 0xff404040, ),
            array(0xff7f7f7f, 0xff7f7f7f, 0xff7f7f7f, 0xff404040, ),
            array(0xff404040, 0xff7f7f7f, 0xff7f7f7f, 0xff7f7f7f, ),
            array(0xff404040, 0xff404040, 0xff7f7f7f, 0xff7f7f7f, ),
    );
    
    private $saturationHsl = array(
            array(0xff7f7f7f, 0xff7f7f7f, 0xff404040, 0xff404040, ),
            array(0xff7f7f7f, 0xff7f7f7f, 0xff7f7f7f, 0xff404040, ),
            array(0xff404040, 0xff7f7f7f, 0xff7f7f7f, 0xff7f7f7f, ),
            array(0xff404040, 0xff404040, 0xff7f7f7f, 0xff7f7f7f, ),
    );
    
    private $saturationHsv = array(
            array(0xffffffff, 0xffffffff, 0xff808080, 0xff808080, ),
            array(0xffffffff, 0xffffffff, 0xffffffff, 0xff808080, ),
            array(0xff808080, 0xffffffff, 0xffffffff, 0xffffffff, ),
            array(0xff808080, 0xff808080, 0xffffffff, 0xffffffff, ),
    );
    
    private $dmin = array(
            array(0xff000000, 0xff000000, 0xff000000, 0xff000000, ),
            array(0xff000000, 0xff000000, 0xff000000, 0xff000000, ),
            array(0xff000000, 0xff000000, 0xff000000, 0xff000000, ),
            array(0xff000000, 0xff000000, 0xff000000, 0xff000000, ),
    );
    
    private $dmax = array(
            array(0xffffffff, 0xffffffff, 0xff808080, 0xff808080, ),
            array(0xffffffff, 0xffffffff, 0xffffffff, 0xff808080, ),
            array(0xff808080, 0xffffffff, 0xffffffff, 0xffffffff, ),
            array(0xff808080, 0xff808080, 0xffffffff, 0xffffffff, ),
    );
    
    private $luminosity = array(
            array(0xff4d4d4d, 0xffa5a5a5, 0xff343434, 0xff343434, ),
            array(0xffe3e3e3, 0xff4d4d4d, 0xffa5a5a5, 0xff343434, ),
            array(0xff4c4c4c, 0xffe3e3e3, 0xff4d4d4d, 0xffa5a5a5, ),
            array(0xff4c4c4c, 0xff4c4c4c, 0xffe3e3e3, 0xff4d4d4d, ),
    );
    
    private $quantize = array(
            array(0xff555555, 0xff808080, 0xff555555, 0xff555555, ),
            array(0xffaaaaaa, 0xff555555, 0xff808080, 0xff555555, ),
            array(0xff404040, 0xffaaaaaa, 0xff555555, 0xff808080, ),
            array(0xff404040, 0xff404040, 0xffaaaaaa, 0xff555555, ),
    );
    
    private static $image = null;
    
    protected function setUp() {
        parent::setUp();
        if (self::$image === null) {
            self::$image = $this->load('nn-4x4.png');
            //self::$image = $this->load('alyson.jpg');
        }
    }
    
    protected function tearDown() {
        parent::tearDown();
        self::$image = null;
    }
        
    private function performGrayscale($algorithm) {
        $gray = null;
        $expected = $this->$algorithm;
        
        $sw = Stopwatch::createStarted();
        switch ($algorithm) {
            case 'grayscale':
                $gray = Grayscaler::grayscale(self::$image);
                break;
            case 'grayscalef':
                $gray = Grayscaler::grayscalef(self::$image);
                break;
            case 'average':
                $gray = Grayscaler::average(self::$image);
                break;
            case 'lightness':
                $gray = Grayscaler::lightness(self::$image);
                break;
            case 'saturationHsl':
                $gray = Grayscaler::saturationHsl(self::$image);
                break;
            case 'saturationHsv':
                $gray = Grayscaler::saturationHsv(self::$image);
                break;
            case 'dmin':
                $gray = Grayscaler::decompositionMin(self::$image);
                break;
            case 'dmax':
                $gray = Grayscaler::decompositionMax(self::$image);
                break;
            case 'luminosity':
                $gray = Grayscaler::luminosity(self::$image);
                break;
            case 'quantize':
                $gray = Grayscaler::quantize(self::$image);
                break;
        }
        
        //$this->save($gray, 'tests/Filter/' . $algorithm . '.jpg');
        //echo 'Elapsed ' . $sw->stop()->elapsed() . 'ms for algorithm ' . $algorithm . "\n";
        
        $this->assertImageArrayEquals($expected, $gray);
    }
    
    public function testGrayscale() {
        $this->performGrayscale('grayscale');
    }
    
    public function testGrayscalef() {
        $this->performGrayscale('grayscalef');
    }
    
    public function testAverage() {
        $this->performGrayscale('average');
    }
    
    public function testLightness() {
        $this->performGrayscale('lightness');
    }
    
    public function testSaturationHsl() {
        $this->performGrayscale('saturationHsl');
    }
    
    public function testSaturationHsv() {
        $this->performGrayscale('saturationHsv');
    }
    
    public function testDecompositionMin() {
        $this->performGrayscale('dmin');
    }
    
    public function testDecompositionMax() {
        $this->performGrayscale('dmax');
    }
    
    public function testLuminosity() {
        $this->performGrayscale('luminosity');
    }
    
    public function testQuantize() {
        $this->performGrayscale('quantize');
    }
}
