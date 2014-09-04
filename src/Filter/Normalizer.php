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
use Taakerman\Pipa\Analysis\RgbHistogram;
use Taakerman\Pipa\Util\Normalization\NormalizationFunction;
use Taakerman\Pipa\Util\Normalization\LinearNormalization;

/**
 * A class to normalize an image, adjusting the intensity of the pixel values.
 * Also known as contrast stretching or histogram stretching (not to be confused 
 * with histogram equalization or mapping).
 * 
 * @see Taakerman\Pipa\Filter\HistogramEqualizer
 * @link http://en.wikipedia.org/wiki/Normalization_(image_processing)
 */
class Normalizer {
    public static function normalize(Pipa $image, Pipa $dst = null, NormalizationFunction $normalizationFunction = null) {
        if ($dst === null) {
            // create a new array image
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        if ($normalizationFunction === null) {
            // create linear normalizer
            $normalizationFunction = new LinearNormalization();
        }
        
        $hist = RgbHistogram::calculate($image);
        $rngR = array(
            self::firstKeyOfMinValue($hist[0]),
            self::lastIndexWithValueGreaterThan($hist[0])
        );
        $rngG = array(
            self::firstKeyOfMinValue($hist[1]),
            self::lastIndexWithValueGreaterThan($hist[1])
        );
        $rngB = array(
            self::firstKeyOfMinValue($hist[2]),
            self::lastIndexWithValueGreaterThan($hist[2])
        );
        $rng = array(0.0, 255.0);
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $norm = array(
                    $normalizationFunction->normalize($color->r, $rngR, $rng), 
                    $normalizationFunction->normalize($color->g, $rngG, $rng), 
                    $normalizationFunction->normalize($color->b, $rngB, $rng), 
                );

                $dst->setPixel($x, $y, Color::fromRgb($norm)->toInt());
            }
        }
        
        return $dst;
    }
    
    private static function firstKeyOfMinValue(&$arr, $threshold = 0) {
        $lastKey = 0;
        
        foreach ($arr as $k => $v) {

            // if we hit the threshold, then just return
            if ($v > $threshold) {
                return $k;
            }
            
            $lastKey = $k;
        }
        
        return $lastKey;
    }
    
    private static function lastIndexWithValueGreaterThan(&$arr, $threshold = 0) {
        //return self::firstKeyOfMinValue(array_reverse($arr), $threshold);
        $size = count($arr);
        
        for ($i = $size - 1; $i >= 0; --$i) {
            $v = $arr[$i];
            
            if ($v > $threshold) {
                return $i;
            }
        }
        
        return 0;
    }
}
