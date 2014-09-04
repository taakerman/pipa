<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Filter\Quantization;

use Taakerman\Pipa\Color\Palette\Palette;
use Taakerman\Pipa\Filter\Quantization\PaletteDistanceQuantizer;
use Taakerman\Pipa\Util\Distance\EuclideanDistance;

/**
 * A Least Squares Quantizer aka a minimization of the Euclidean Distance
 * to a given color of a given Palette, is a naive quantization technique
 * which works pretty well
 * 
 * @link http://en.wikipedia.org/wiki/Least_squares
 * @link http://en.wikipedia.org/wiki/Euclidean_distance
 * @link http://msdn.microsoft.com/en-us/library/aa479306.aspx
 */
class LeastSquaresQuantizer extends PaletteDistanceQuantizer {
    /**
     * Constructs a new least squares quantizer
     * 
     * @param \Taakerman\Pipa\Color\Palette $palette
     */
    public function __construct(Palette $palette) {
        parent::__construct(new EuclideanDistance(), $palette);
    }
}
