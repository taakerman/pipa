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

use Taakerman\Pipa\Util\Gamma\GammaFunction;

/**
 * The sRGB gamma correcting function as defined in 
 * {@link http://en.wikipedia.org/wiki/SRGB} and 
 * {@link http://en.wikipedia.org/wiki/Grayscale}
 */
class SrgbGammaFunction implements GammaFunction {
    public function compress($n) {
        if ($n <= 0.0031308) {
            return $n * 12.92;
        } else {
            return 1.055 * pow($n, 1/2.4) - 0.055;
        }
    }

    public function expand($n) {
        if ($n <= 0.04045) {
            return $n / 12.92;
        } else {
            return pow(($n + 0.055) / 1.055, 2.4);
        }
    }

}