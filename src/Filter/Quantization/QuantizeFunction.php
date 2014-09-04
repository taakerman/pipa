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

use Taakerman\Pipa\Color\Color;

/**
 * A quantizer that quantizes (reduces) colors 
 */
interface QuantizeFunction {
    /**
     * Quantizes a color
     * 
     * @param \Taakerman\Pipa\Color\Color $color
     * @return \Taakerman\Pipa\Color\Color
     */
    function quantize(Color $color);
}
