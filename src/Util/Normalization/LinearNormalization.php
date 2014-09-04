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
 * A standard linear normalization
 */
class LinearNormalization implements NormalizationFunction {
    public function normalize($n, array &$range, array &$newRange) {
        list($min, $max) = $range;
        list($newMin, $newMax) = $newRange;
        
        $coeff = ($newMax - $newMin) / ($max - $min);
        return ($n - $min) * ($coeff) + $newMin;
    }

}
