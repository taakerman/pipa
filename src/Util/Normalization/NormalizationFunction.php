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

/**
 * A normalization function that can normalize a value in a given range
 * into a new range; Dynamic Range Expansion
 * 
 * @link http://en.wikipedia.org/wiki/Normalization_(statistics)
 * @link http://en.wikipedia.org/wiki/Normalization_(image_processing)
 */
interface NormalizationFunction {
    /**
     * Adjusts $n in a $range to be in the new range, thus re-scaling $n to a 
     * common scale (normalization of ratings).
     * 
     * @param float $n
     * @param array $range array containing min and max of the current scale
     * @param array $newRange array containing min and max of the new scale
     * @return float the normalized value
     */
    function normalize($n, array &$range, array &$newRange);
}