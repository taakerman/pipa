<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util\Distance;

use Taakerman\Pipa\Util\Distance\DistanceFunction;
use Exception;

/**
 * The Euclidean distance measure
 * 
 * @link http://en.wikipedia.org/wiki/Euclidean_distance
 */
class EuclideanDistance implements DistanceFunction {
    public function cartesians($p, $q) {
        $p = (is_array($p)) ? $p : array($p);
        $q = (is_array($q)) ? $q : array($q);
        
        $pLen = count($p);
        $qLen = count($q);
        
        if ($pLen != $qLen) {
            throw new Exception('p and q must be of equal length');
        }
        
        $sum = 0;
        for ($i = 0; $i < $pLen; ++$i) {
            $sum += pow($p[$i] - $q[$i], 2);
        }
        
        return sqrt($sum);
    }
}
