<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util\Normalization;

use Taakerman\Pipa\Util\Normalization\NormalizationFunction;

/**
 * A standard Sigmoidal normalization
 */
class SigmoidNormalization implements NormalizationFunction {
    private $width;
    private $center;
    
    public function __construct($width = 5.0, $center = 128.0) {
        $this->width = $width; // alpha
        $this->center = $center; // beta
    }
    
    public function normalize($n, array &$range, array &$newRange) {
        list($newMin, $newMax) = $newRange;

        $frac = (($n - $this->center) / $this->width);
        $denom = 1.0 + exp(-1.0 * $frac);
        return ($newMax - $newMin) * (1.0 / $denom) + $newMin;
    }
}
