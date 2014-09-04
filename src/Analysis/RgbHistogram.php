<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Analysis;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Color\Color;

/**
 * Calculation of color histogram in the RGB space
 * 
 * @link http://staff.science.uva.nl/~rein/UvAwiki/uploads/CV0708/swainballard.pdf
 */
class RgbHistogram {
    const CHANNEL_R = 0x01;
    const CHANNEL_G = 0x02;
    const CHANNEL_B = 0x03;
    
    /**
     * Calculate a color histogram for an image in the RGB space
     * 
     * @param \Taakerman\Pipa\BaseImage $image the image to calculate a histogram for
     * @return array the histogram
     */
    public static function calculate(Pipa $image, $bins = 256) {
        $rChannel = array_fill(0, $bins, 0);
        $gChannel = array_fill(0, $bins, 0);
        $bChannel = array_fill(0, $bins, 0);

        $binSize = $bins / 256.0;
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                $rBin = (int) ($color->r / $binSize);
                $gBin = (int) ($color->g / $binSize);
                $bBin = (int) ($color->b / $binSize);

                ++$rChannel[$rBin];
                ++$gChannel[$gBin];
                ++$bChannel[$bBin];
            }
        }
        
        return array($rChannel, $gChannel, $bChannel);
    }
    
    /**
     * Calculate a color histogram for an image in the RGB space
     * 
     * @param \Taakerman\Pipa\BaseImage $image the image to calculate a histogram for
     * @return array the histogram
     */
    public static function calculateChannel(Pipa $image, $channel = self::CHANNEL_R, $bins = 256) {
        $hist = array_fill(0, $bins, 0);

        $binSize = $bins / 256.0;
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
                
                if ($channel == self::CHANNEL_R) {
                    $bin = (int) ($color->r / $binSize);
                } else if ($channel == self::CHANNEL_G) {
                    $bin = (int) ($color->g / $binSize);
                } else {
                    $bin = (int) ($color->b / $binSize);
                }

                ++$hist[$bin];
            }
        }
        
        return $hist;
    }
}
