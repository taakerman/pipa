<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Transform\Interpolate;

use Taakerman\Pipa\Image\Pipa;
use Taakerman\Pipa\Image\PipaMemory;

class Bilinear {
    public static function interpolate(Pipa $image, $newWidth, $newHeight) {
        $dst = PipaMemory::fromSize($newWidth, $newHeight);
        
        $w = $image->getWidth();
        $h = $image->getHeight();
        
        $xRatio = ($w - 1.0) / $newWidth;
        $yRatio = ($h - 1.0) / $newHeight;
        
        // TODO count dx and dy up
        $dx = 0;
        $dy = 0;
        for ($x = 0; $x < $w; ++$x) {
            for ($y = 0; $y < $h; ++$h) {
                $xDiff = $xRatio * $x;
                $xDiff = $xDiff - floor($xDiff);
                $yDiff = $yRatio * $y;
                $yDiff = $yDiff - floor($yDiff);
                
                // todo, check bounds ;)
                $a = $image->getPixel($x,$y);
                $b = $image->getPixel($x+1, $y);
                $c = $image->getPixel($x, $y+1);
                $d = $image->getPixel($x+1, $y+1);
                
                $blue = ($a & 0xFF) * (1 - $xDiff) * (1 - $yDiff) + 
                        ($b & 0xFF) * ($xDiff) * (1 - $yDiff) +
                        ($c & 0xFF) * ($yDiff) * (1 - $xDiff) + 
                        ($d & 0xFF) * ($xDiff * $yDiff);
                
                $green = (($a >> 8) & 0xFF) * (1 - $xDiff) * (1 - $yDiff) + 
                        (($b >> 8) & 0xFF) * ($xDiff) * (1 - $yDiff) +
                        (($c >> 8) & 0xFF) * ($yDiff) * (1 - $xDiff) + 
                        (($d >> 8) & 0xFF) * ($xDiff * $yDiff);
                
                $red = (($a >> 16) & 0xFF) * (1 - $xDiff) * (1 - $yDiff) +
                        (($b >> 16) & 0xFF) * ($xDiff) * (1 - $yDiff) +
                        (($c >> 16) & 0xFF) * ($yDiff) * (1 - $xDiff) + 
                        (($d >> 16) & 0xFF) * ($xDiff * $yDiff);
                
                $alpha = 0xFF;
                
                $newPixel = ($alpha << 24) | 
                    ($red << 16) | 
                    ($green <<  8) | 
                    ($blue);
                
                $dst->setPixel($dx, $dy, $newPixel);
            }
        }
    }
}

// from http://tech-algorithm.com/articles/bilinear-image-scaling/
/**
 * Bilinear resize ARGB image.
 * pixels is an array of size w * h.
 * Target dimension is w2 * h2.
 * w2 * h2 cannot be zero.
 * 
 * @param pixels Image pixels.
 * @param w Image width.
 * @param h Image height.
 * @param w2 New width.
 * @param h2 New height.
 * @return New array with size w2 * h2.
 *//*
public int[] resizeBilinear(int[] pixels, int w, int h, int w2, int h2) {
    int[] temp = new int[w2*h2] ;
    int a, b, c, d, x, y, index ;
    float x_ratio = ((float)(w-1))/w2 ;
    float y_ratio = ((float)(h-1))/h2 ;
    float x_diff, y_diff, blue, red, green ;
    int offset = 0 ;
    for (int i=0;i<h2;i++) {
        for (int j=0;j<w2;j++) {
            x = (int)(x_ratio * j) ;
            y = (int)(y_ratio * i) ;
            x_diff = (x_ratio * j) - x ;
            y_diff = (y_ratio * i) - y ;
            index = (y*w+x) ;                
            a = pixels[index] ;
            b = pixels[index+1] ;
            c = pixels[index+w] ;
            d = pixels[index+w+1] ;

            // blue element
            // Yb = Ab(1-w)(1-h) + Bb(w)(1-h) + Cb(h)(1-w) + Db(wh)
            blue = (a&0xff)*(1-x_diff)*(1-y_diff) + (b&0xff)*(x_diff)*(1-y_diff) +
                   (c&0xff)*(y_diff)*(1-x_diff)   + (d&0xff)*(x_diff*y_diff);

            // green element
            // Yg = Ag(1-w)(1-h) + Bg(w)(1-h) + Cg(h)(1-w) + Dg(wh)
            green = ((a>>8)&0xff)*(1-x_diff)*(1-y_diff) + ((b>>8)&0xff)*(x_diff)*(1-y_diff) +
                    ((c>>8)&0xff)*(y_diff)*(1-x_diff)   + ((d>>8)&0xff)*(x_diff*y_diff);

            // red element
            // Yr = Ar(1-w)(1-h) + Br(w)(1-h) + Cr(h)(1-w) + Dr(wh)
            red = ((a>>16)&0xff)*(1-x_diff)*(1-y_diff) + ((b>>16)&0xff)*(x_diff)*(1-y_diff) +
                  ((c>>16)&0xff)*(y_diff)*(1-x_diff)   + ((d>>16)&0xff)*(x_diff*y_diff);

            temp[offset++] = 
                    0xff000000 | // hardcode alpha
                    ((((int)red)<<16)&0xff0000) |
                    ((((int)green)<<8)&0xff00) |
                    ((int)blue) ;
        }
    }
    return temp ;
}*/
