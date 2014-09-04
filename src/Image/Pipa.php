<?php 

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Image;

/**
 * Pipa is the base interface for images of Pipa
 * It is very simple and can be easily extended/implemented on top of other 
 * implementations
 */
interface Pipa {
    /**
     * Returns the pixel at (x,y) starting from (0,0) left top to right bottom 
     * (width, height). The format should be 4 bytes corresponding to rgba channels
     * 
     * @param int $x
     * @param int $y
     * @return int integer representing the colors
     */
    function getPixel($x, $y);
    
    /**
     * Sets the pixel at (x, y) 
     * 
     * @param int $x
     * @param int $y
     * @param int $pixel
     */
    function setPixel($x, $y, $pixel);
    
    /**
     * Returns the width of the image
     * 
     * @return the width
     */
    function getWidth();
    
    /**
     * Returns the height of the image
     * 
     * @return int the height
     */
    function getHeight();
}
