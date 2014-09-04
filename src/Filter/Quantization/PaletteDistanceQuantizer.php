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

use Taakerman\Pipa\Util\Distance\DistanceFunction;
use Taakerman\Pipa\Filter\Quantization\QuantizeFunction;
use Taakerman\Pipa\Color\Palette\Palette;
use Taakerman\Pipa\Color\Palette\Palette\WebsafePalette;
use Taakerman\Pipa\Util\Distance\EuclideanDistance;
use Taakerman\Pipa\Color\Color;

/**
 * Uses a distance measure to the given palette in order to find the best
 * quantization
 */
class PaletteDistanceQuantizer implements QuantizeFunction {
    /* @var \Taakerman\Pipa\Util\Distance\DistanceFunction */
    private $distanceFunction;
    
    /* @var \Taakerman\Pipa\Color\Palette\Palette */
    private $palette;
    
    // cache best match colors for speed
    private $cache;
    
    /**
     * Creates a new Palette-based Distance Quantizer
     * 
     * @param \Taakerman\Pipa\Util\Distance\DistanceFunction $distanceFunction the distance function to use (defaults to {@see \Taakerman\Pipa\Util\Distance\EuclideanDistance}
     * @param \Taakerman\Pipa\Color\Palette $palette the palette to use (default to {see \Taakerman\Pipa\Color\Palette\WebsafePalette}
     */
    public function __construct(DistanceFunction $distanceFunction = null, Palette $palette = null) {
        if ($distanceFunction === null) {
            $distanceFunction = new EuclideanDistance();
        }
        
        if ($palette === null) {
            $palette = new WebsafePalette();
        }       
        
        $this->palette = $palette;
        $this->distanceFunction = $distanceFunction;
    }
    
    public function quantize(Color $color) {
        if (isset($this->cache[$color->r][$color->g][$color->b])) {
            return $this->cache[$color->r][$color->g][$color->b];
        }
        
        $leastDistance = PHP_INT_MAX;
        $bestColor = null;
        
        foreach ($this->palette->getColors() as $paletteColor) {
            $distance = $this->distanceFunction->cartesians(
                    $paletteColor->toRgb(), 
                    $color->toRgb()
            );
            
            if ($distance < $leastDistance) {
                $leastDistance = $distance;
                $bestColor = $paletteColor;
                
                if ($leastDistance == 0) {
                    break;
                }
            }
        }
        
        // update cache
        $this->cache[$color->r][$color->g][$color->b] = $bestColor;
        
        return $bestColor;
    }
}
