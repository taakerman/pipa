<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Image\PixelIterator;
use Taakerman\Pipa\Image\ImageLoader;
use Taakerman\Pipa\Image\ImageWriter;
use Taakerman\Pipa\Visualization\Visualizer;
use Taakerman\Pipa\Color\Color;

use Taakerman\Pipa\Resources;

use PHPUnit_Framework_TestCase;

/**
 * A simple test class that contains utility functions for testing
 * Pipa
 */
class PipaUnitTest extends PHPUnit_Framework_TestCase {
    const DELTA = 0.000001;
    
    public function assertImageArrayEquals(array $expected, Pipa $actual) {
        $pixels = new PixelIterator($actual);
        
        /* @var $pixel \Taakerman\Pipa\Image\Pixel */
        foreach ($pixels as $pixel) {
            $x = $pixel->x;
            $y = $pixel->y;
            $a = $pixel->value;
            $e = $expected[$x][$y];
            $ahex = '0x' . $pixel->asColor()->toHexa();
            $ehex = '0x' . Color::fromInt($e)->toHexa();
            
            // assert is expensive, only use when they are different
            if ($e != $a) {
                $this->assertEquals($e, $a, "Expected $ehex but was $ahex");
            }
        }
    }
    
    public function assertImagesNotEquals(Pipa $expected, Pipa $actual) {
        // check sizes
        if ($expected->getWidth() != $actual->getWidth() || $expected->getHeight() != $actual->getHeight()) {
            return;
        }
        
        // check pixels
        $pixels = new PixelIterator($expected);
        
        foreach ($pixels as $pixel) {
            $x = $pixel->x;
            $y = $pixel->y;
            $expectedPixel = $pixel->value;
            $actualPixel = $actual->getPixel($x, $y);
            
            if ($expectedPixel != $actualPixel) {
                // found a difference, just return
                return;
            }
        }
        
        // no differences found
        $this->assertNotEquals(true, true, "Images are equal");
    }
    
    public function assertImagesEquals(Pipa $expected, Pipa $actual) {
        $this->assertEquals($expected->getWidth(), $actual->getWidth(), "images differ in width");
        $this->assertEquals($expected->getHeight(), $actual->getHeight(), "images differ in height");
        
        $pixels = new PixelIterator($expected);
        
        foreach ($pixels as $pixel) {
            $x = $pixel->x;
            $y = $pixel->y;
            $expectedPixel = $pixel->value;
            $actualPixel = $actual->getPixel($x, $y);
            
            // assert is expensive, only use when they are different
            if ($expectedPixel != $actualPixel) {
                var_dump(Color::fromInt($expectedPixel)->toRgba());
                var_dump(Color::fromInt($actualPixel)->toRgba());
                $this->assertEquals($expectedPixel, $actualPixel, "pixels differ at ($x,$y)");
            }
        }
    }
    
    public function imageToPhpArray(Pipa $image, $varName = 'var') {
        echo Visualizer::imageToPhpArray($image, $varName) . "\n";
    }
    
    public function histogramsToPhpArray(array $histograms, $varName) {
        $c = count($histograms);
        for ($i = 0; $i < $c; ++$i) {
            $this->histogramToPhpArray($histograms[$i], $varName . ($i+1));
        }
    }
    
    public function histogramToPhpArray($histogram, $varName) {
        echo Visualizer::histogramToPhpArray($histogram, $varName) . "\n";
    }
    
    public function saveHistogram($histogram, $path) {
        $image = Visualizer::visualizeHistogram($histogram);
        $image->save($path);
    }
    
    public function save(Pipa $image, $path) {
        return ImageWriter::writer()
                ->path($path)
                ->write($image);
    }
    
    public function load($path) {
        return ImageLoader::loader()
                ->path(Resources::pathOf($path))
                ->load();
    }
}