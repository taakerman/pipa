<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Visualization;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Color\Color;
use Taakerman\Pipa\Util\StringBuilder;
use Intervention\Image\ImageManagerStatic as IM;

/**
 * A simple visualizer used to visualize stuff during testing
 */
class Visualizer {
    
    public static function visualizeRgbHistogram(array $histogram) {
        $redChannel = $histogram[0];
        $greenChannel = $histogram[1];
        $blueChannel = $histogram[2];
        
        $highest = max(
                max($redChannel), 
                max($greenChannel), 
                max($blueChannel)
        );
        $scale = (int) ($highest / 128);
        
        /* @var $histogramImage \Intervention\Image\Image */
        $histogramImage = IM::canvas(256, 128, '#ffffff');
        
        $lastX = 0;
        $lastY = 128;
        foreach ($redChannel as $idx => $freq) {
            //echo "$idx: $freq\n";
            //$histogramImage->pixel('#0000ff', (int) $idx, (int) ($freq / $scale));
            $y2 = 128 - ((int) ceil($freq / $scale));
            $histogramImage->line($lastX, $lastY, $idx, $y2, function ($draw) {
                $draw->color('#ff0000');
            });
            
            $lastX = $idx;
            $lastY = $y2;
        }
        
        $lastX = 0;
        $lastY = 128;
        foreach ($greenChannel as $idx => $freq) {
            //echo "$idx: $freq\n";
            //$histogramImage->pixel('#00ff00', (int) $idx, (int) ($freq / $scale));
            
            $y2 = 128 - ((int) ceil($freq / $scale));
            $histogramImage->line($lastX, $lastY, $idx, $y2, function ($draw) {
                $draw->color('#00ff00');
            });
            
            $lastX = $idx;
            $lastY = $y2;
        }
        
        $lastX = 0;
        $lastY = 128;
        foreach ($blueChannel as $idx => $freq) {
            //echo "$idx: $freq\n";
            //$histogramImage->pixel('#ff0000', (int) $idx, (int) ($freq / $scale));
            $y2 = 128 - ((int) ceil($freq / $scale));
            $histogramImage->line($lastX, $lastY, $idx, $y2, function ($draw) {
                $draw->color('#0000ff');
            });
            
            $lastX = $idx;
            $lastY = $y2;
        }
        
        return $histogramImage;
    }
    
    public static function visualizeHistogram(array $histogram) {

        $highest = max($histogram);
        $scale = (int) ($highest / 128);
        
        /* @var $histogramImage \Intervention\Image\Image */
        $histogramImage = IM::canvas(256, 128, '#ffffff');
        
        foreach ($histogram as $idx => $freq) {
            //echo "$idx: $freq\n";
            //$histogramImage->pixel('#000000', (int) $idx, 128 - ((int) ($freq / $scale)));
            $y2 = 128 - ((int) ceil($freq / $scale));
            $histogramImage->line($idx, 127, $idx, $y2, function ($draw) {
                $draw->color('#000000');
            });
        }

        return $histogramImage;
    }
    
    public static function histogramToPhpArray(array $histogram, $variableName = 'var', $keys = false) {
        $builder = new StringBuilder();
        
        $builder->append('$')
                ->append($variableName)
                ->append(' = array(');
        
        foreach ($histogram as $c => $f) {
            if ($keys) {
                $builder->append($c)
                        ->append('=>')
                        ->append($f)
                        ->append(',');
            } else {
                $builder->append($f)
                        ->append(',');
            }
        }
        
        return $builder
                ->appendln(');')
                ->build();
    }
    
    public static function imageToPhpArray(Pipa $image, $variableName = 'var', $hex = true) {
        $builder = new StringBuilder();
        $lastX = 0;
        
        $builder->append('$')
                ->append($variableName)
                ->append(' = array(')
                ->nl()
                ->tab()
                ->append('array(');

        $width = $image->getWidth();
        $height = $image->getHeight();
        for ($x = 0; $x < $width; ++$x) {
            for ($y = 0; $y < $height; ++$y) {
                $value = $image->getPixel($x, $y);
                $color = Color::fromInt($value);
            
                if ($x != $lastX && $x != 0) {
                    $lastX = $x;
                    $builder->appendln('),')
                            ->tab()
                            ->append('array(');
                }

                if ($hex) {
                    $builder->appendf('0x%8s', $color->toHexa())
                            ->append(', ');                
                } else {
                    $builder->appendf('%8d', $color->toInt())
                            ->append(', ');
                }
            }
        }
        
        $builder->appendln('),')
                ->appendln(');');
        
        return $builder->build();
    }
}
