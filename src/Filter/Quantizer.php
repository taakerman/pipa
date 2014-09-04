<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Filter;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Filter\Quantization\QuantizeFunction;

/**
 * A class for quantizing images
 * The quantization of an image, means reducing its colors, to a more 
 * managable set of colors
 * TODO create a standard quantizer
 * 
 * @link http://en.wikipedia.org/wiki/Quantization_(image_processing)
 * @link http://en.wikipedia.org/wiki/Color_quantization
 */
class Quantizer {
    /**
     * Quantizes an image using the specified quantizer
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to quantize
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image (defaults to a new image)
     * @param \Taakerman\Pipa\Filter\Quantization\QuantizeFunction $quantizer the quantizer to use (defaults to {@link \Taakerman\Pipa\Filter\Quantizer\LeastSquaresQuantizer} with a {@link \Taakerman\Pipa\Filter\Quantizer\Palette\WebsafePalette})
     * @return \Taakerman\Pipa\Image\Pipa the quantized image
     */
    public static function quantize(Pipa $image, Pipa $dst = null, QuantizeFunction $quantizeFunction = null) {
        if ($dst === null) {
            // create a new array image
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $q = $quantizeFunction->quantize($color);
                $q->a = $color->a;

                $dst->setPixel($x, $y, $q->toInt());
            }
        }
        
        return $dst;
    }
}
