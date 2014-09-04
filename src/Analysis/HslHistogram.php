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
 * The HslHistogram calculates histograms based on the the HSL color space
 * 
 * @link http://en.wikipedia.org/wiki/HSL_and_HSV
 * @link http://en.wikipedia.org/wiki/Image_histogram
 * @link http://en.wikipedia.org/wiki/Color_histogram
 */
class HslHistogram {
    const CHANNEL_H = 0x01;
    const CHANNEL_S = 0x02;
    const CHANNEL_L = 0x03;
    
    /**
     * Calculate the histogram for each HSL channel
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to calculate for
     * @param type $bins the number of bins (defaults to 256)
     * @return array the histograms of the HSL channels
     */
    public static function calculate(Pipa $image, $bins = 256) {
        $hChannel = array_fill(0, $bins, 0);
        $sChannel = array_fill(0, $bins, 0);
        $lChannel = array_fill(0, $bins, 0);

        $binSize = $bins / 256.0;
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                
                list($h,$s,$l) = Color::fromInt($value)->toHsl();

                // need to ensure HSL values are in the 256 range
                $h = ($h * 255.0) / 360.0;
                $s = $s * 255.0;
                $l = $l * 255.0;

                // find the correct bin
                $hBin = (int) ($h / $binSize);
                $sBin = (int) ($s / $binSize);
                $lBin = (int) ($l / $binSize);

                ++$hChannel[$hBin];
                ++$sChannel[$sBin];
                ++$lChannel[$lBin];
            }
        }
        
        return array($hChannel, $sChannel, $lChannel);
    }
    
    /**
     * Calculates for a single channel (see constants of this class)
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to calculate for
     * @param type $channel the channel to calculate for (defaults to L)
     * @param type $bins the number of bins (defaults to 256)
     * @return array the histogram
     */
    public static function calculateChannel(Pipa $image, $channel = self::CHANNEL_L, $bins = 256) {
        $hist = array_fill(0, $bins, 0);
        $binSize = $bins / 256.0;
        
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                list($h,$s,$l) = Color::fromInt($value)->toHsl();

                $ch = 0;
                if ($channel == self::CHANNEL_H) {
                    $ch = ($h * 255.0) / 360.0;
                } else if ($channel == self::CHANNEL_S) {
                    $ch = $s * 255.0;
                } else {
                    $ch = $l * 255.0;
                }

                $bin = (int) ($ch / $binSize);
                ++$hist[$bin];
            }
        }
        
        return $hist;
    }
}
