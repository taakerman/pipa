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

/**
 * The ImageHash is a hash of an image and can be used for similarity checking
 * http://www.cs.nott.ac.uk/~qiu/webpages/Papers/IEE-Proc2000.pdf
 */
class ImageHash {
    private $hash;
    
    protected function __construct($hash) {
        $this->hash = $hash;
    }
    
    public static function fromHex($hex) {
        return new ImageHash($hex);
    }
    
    public static function fromBitString($bitString) {
        $bytes = str_split($bitString, 8);
        $hex = '';
        
        // convert each 'byte' separately
        foreach ($bytes as $byte) {
            $hex .= str_pad(dechex(bindec($byte)), 2, '0', STR_PAD_LEFT);
        }
        
        return new ImageHash($hex);
    }
    
    public function getHash() {
        return $this->hash;
    }
    
    public function similarity(ImageHash $hash) {
        if (strlen($this->hash) != strlen($hash->getHash())) {
            throw new Exception('Unable to compare hashes not of equal length');
        }
        
        // compute hamming distance
        $hex1 = str_split($this->hash, 2);
        $hex2 = str_split($hash->getHash(), 2);
        
        $coefficients = array();
        for ($i = 0; $i < count($hex1); $i++) {
            $d1 = hexdec($hex1[$i]);
            $d2 = hexdec($hex2[$i]);
            
            $dist = 0;
            $val = $d1 ^ $d2;
            
            while ($val) {
                ++$dist;
                $val &= $val - 1;
            }
            
            $coefficients[] = $dist;
        }

        // now we have distance for all coefficients
        // the max distance if they are completely different is 
        // 8 (bit per byte) times the number of coefficients
        $scale = 8 * count($coefficients);
        
        // sum and scale, and reverse distance to a measure of similarity between 0..1
        return 1 - (array_sum($coefficients) / $scale);
    }
}