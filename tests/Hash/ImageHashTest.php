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

use Taakerman\Pipa\Hash\ImageHash;
use Taakerman\Pipa\Hash\Functions\LowResolutionAverageHash;

use Taakerman\Pipa\PipaUnitTest;

class ImageHashTest extends PipaUnitTest {
    public function testHashSimilarity() {
        $image1 = $this->load('alyson.jpg');
        $image2 = $this->load('DSC_3195.jpg');

        
        $imageHash1 = LowResolutionAverageHash::hash($image1);
        $imageHash2 = LowResolutionAverageHash::hash($image2);
        
        $hash1 = $imageHash1->getHash();
        $hash2 = $imageHash2->getHash();
        
        $this->assertNotEquals($hash2, $hash1);
        
        $similarity = $imageHash1->similarity($imageHash2);
        $this->assertEquals(0.375, $similarity, 'alyson and DSC_3195 are not similar by 0.375', self::DELTA);
    }
    
    public function testSameSimilarity() {
        $hash1 = ImageHash::fromHex('ffffcf87abc3ffe0');
        $hash2 = ImageHash::fromHex('ffffcf87abc3ffe0');
        $hash3 = ImageHash::fromHex('0408003f7f7f7f3e');    
        $hash4 = ImageHash::fromHex('0408003f7f7f7f3e');

        $this->assertEquals(1.0, $hash1->similarity($hash2), '#1 and #2 are not similar by 1', self::DELTA);
        $this->assertEquals(1.0, $hash3->similarity($hash4), '#3 and #4 are not similar by 1', self::DELTA);
    }
    
    public function testNoSimilarity() {
        $hash1 = ImageHash::fromHex('ffffcf87abc3ffe0');
        $hash2 = ImageHash::fromHex('0408003f7f7f7f3e');

        $this->assertEquals(0.375, $hash1->similarity($hash2), '#1 and #2 are not similar by 0.375', self::DELTA);
    }
    
    /*
    public function testMultiple() {
        $hashes = array();
        $results = array();
        
        $files = glob('/images/*.{jpg,png,gif}', GLOB_BRACE);
        foreach($files as $file) {
            $image = ImageLoader::loader()->path($file)->load();
            $hash = LowResolutionAverageHash::hash($image);
            $hashes[$file] = $hash;
        }
        
        foreach ($hashes as $file1 => $hash1) {
            $top = new TopMatcher(5);
                        
            foreach ($hashes as $file2 => $hash2) {
                $sim1 = $hash1->similarity($hash2);
                $node = new TopNode($sim1, $file2);
                $top->put($node);
            }
            
            $results[$file1] = $top;
        }
        
        foreach ($results as $filename => $top) {
            echo 'File: ' . $filename . ' has top matches: ' . "\n";
            $matches = $top->toArray();
            foreach ($matches as $match) {
                if ($match->getWeight() > 0.85) {
                    $weight = sprintf('%.1f%%', round($match->getWeight() * 100));
                    echo $weight . " - " . $match->getValue() . "\n";
                }
            }
            
            echo "\n";
        }
    }*/
            
}