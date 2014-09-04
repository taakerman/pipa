<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util\Gamma;

/**
 * An interface for gamma correction functions
 * 
 * @link http://en.wikipedia.org/wiki/Gamma_correction
 * @link http://poynton.com/papers/IST_SPIE_9801/index.html
 */
interface GammaFunction {
    /**
     * Performs gamma expansion on a value in the range 0..1
     * 
     * @param float $n a floating point between 0 and 1
     * @return float the expanded gamma correction of $n
     */
    function expand($n);
    
    /**
     * Performs gamme compression (the inverse of expansion) in the range 0..1
     * 
     * @param float $n a floating point between 0 and 1
     * @return float the compressed gamma correction of $n
     */
    function compress($n);
}
