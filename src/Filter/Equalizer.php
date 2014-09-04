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
use Taakerman\Pipa\Analysis\Histogram;
use Taakerman\Pipa\Analysis\HslHistogram;
use Taakerman\Pipa\Analysis\RgbHistogram;
use Taakerman\Pipa\Image\PipaMemory;
use Taakerman\Pipa\Color\Color;

/**
 * An Image Equalizer based on histograms for the equalization (not to be 
 * confused with Image Normalization)
 * 
 * @see \Taakerman\Pipa\Filter\Normalizer
 * @link http://poseidon.csd.auth.gr/papers/PUBLISHED/JOURNAL/pdf/Bassiou07a.pdf
 * @link http://en.wikipedia.org/wiki/Histogram_equalization
 * @link http://en.wikipedia.org/wiki/Gamut
 */
class Equalizer {
    /**
     * Equalizes a already grayscaled image, based on a histogram
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the grayscaled image to equalize
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image
     * @param type $maxIntensity the maximum intensity (default to 256)
     * @param array $histogram the histogram to use (default to a standard histogram)
     * @return \Taakerman\Pipa\Image\PipaMemory
     */
    public static function grayscaled(Pipa $image, Pipa $dst = null, $maxIntensity = 256, array $histogram = null) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        if ($histogram === null) {
            // create new histogram
            $histogram = Histogram::calculate($image);
        }
        
        // histogram may have bins, but will most often be 256
        $bins = count($histogram);
        $binSize = $bins / 256.0;
        
        // build accumulated normalized histogram (CDF - Cumulative Distribution Function)
        $cdf = array_fill(0, $bins, 0);
        $n = $image->getWidth() * $image->getHeight();
        $sum = 0;
        for ($i = 0; $i < $bins; ++$i) {
            $sum += $histogram[$i] / $n;
            // by multiplying the max intensity now, we avoid doing it for each pixel
            // and will only have to do it for each lookup
            $cdf[$i] = $sum * $maxIntensity;
        }
        
        // apply cdf
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);

                // pick arbitrary channel as we assume grayscale image
                $channel = $color->r;

                // find the correct bin
                $bin = (int) ($channel / $binSize);

                // lookup the mapping and set the pixel
                $dst->setPixel($x, $y, Color::fromChannel($cdf[$bin])->toInt());
            }
        }
        
        return $dst;
    }
    
    // 
    /**
     * Equalizes a color image based on each RGB channel intensity
     * 
     * Based on {@link http://zerocool.is-a-geek.net/java-image-histogram-equalization/}
     * 
     * Note that this implementation suffers from The Gamut Problem (see the 
     * notes of the wikipedia article 
     * {@link http://en.wikipedia.org/wiki/Histogram_equalization} or 
     * {@link http://ieeexplore.ieee.org/xpl/articleDetails.jsp?reload=true&arnumber=1257395})
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to equalize
     * @param \Taakerman\Pipa\Image\Pipa $dst the destination image
     * @param type $maxIntensity the maximum intensity (defaults to 256)
     * @return \Taakerman\Pipa\Image\PipaMemory the equalized image
     */
    public static function rgb(Pipa $image, Pipa $dst = null, $maxIntensity = 256) {
        if ($dst === null) {
            // just create an ArrayImage
            $dst = PipaMemory::fromSize($image->getWidth(), $image->getHeight());
        }
        
        list($rHist, $gHist, $bHist) = RgbHistogram::calculate($image);
            
        // build accumulated normalized histogram (CDF - Cumulative Distribution Function)
        $cdf = array(
            'r' => array_fill(0, 256, 0), 
            'g' => array_fill(0, 256, 0), 
            'b' => array_fill(0, 256, 0), 
        );
        
        // fill all
        $n = $image->getWidth() * $image->getHeight();
        $rSum = 0;
        $gSum = 0;
        $bSum = 0;
        for ($i = 0; $i < 256; ++$i) {
            
            $rSum += $rHist[$i] / $n;
            $gSum += $gHist[$i] / $n;
            $bSum += $bHist[$i] / $n;
            
            // by multiplying the max intensity now, we avoid doing it for each pixel
            // and will only have to do it for each lookup
            // http://www.songho.ca/dsp/histogram/histogram.html
            $cdf['r'][$i] = (int) ($rSum * $maxIntensity);
            $cdf['g'][$i] = (int) ($gSum * $maxIntensity);
            $cdf['b'][$i] = (int) ($bSum * $maxIntensity);
        }
        
        // apply cdf to rgb space
        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);

                // lookup in cdf
                $r = $cdf['r'][$color->r];
                $g = $cdf['g'][$color->g];
                $b = $cdf['b'][$color->b];
                $a = $color->a;

                // lookup the mapping and set the pixel
                $dst->setPixel($x, $y, Color::fromRgba(array($r, $g, $b, $a))->toInt());
            }
        }
        
        return $dst;
    }
}
