<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Hash\Functions;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Analysis\Histogram;
use Taakerman\Pipa\Hash\ImageHash;
use Taakerman\Pipa\Hash\ImageHashFunction;

/**
 * The HistogramHash produces a histogram hash of the given image
 * TODO Work In Progress
 */
class HistogramHash implements ImageHashFunction {
    private $hash;
    
    public function __construct(Pipa $image, $bins = 128) {
   
        $hist = Histogram::calculate($image, $bins);
        $size = $image->getHeight() * $image->getWidth();
        
        // scale to size / bins allows each bin to be between 0..bin
        $scale = $size / $bins;
        
        $normalized = array_map(
            function($value) use ($scale) {
                return (int) round($value / $scale);
            }, 
            $hist
        );
        
        $hex = '';
        foreach ($normalized as $value) {
            $hex .= str_pad(dec2hex($value), '0', STR_PAD_LEFT);
        }
        
        $this->hash = ImageHash::fromHex($hex);
    }
    
    public function getHash() {
        return $this->hash;
    }
}
