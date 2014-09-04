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

use Taakerman\Pipa\Image\Pipa;

/**
 * Driver handles I/O functions
 * Heavily inspired by the great Intervention/Image PHP library
 * 
 * @link http://image.intervention.io
 * @link https://github.com/Intervention/image
 */
interface Driver {
    /**
     * Returns true if driver is enabled
     * 
     * @return boolean true if driver is enabled, false otherwise
     */
    function isEnabled();
    
    /**
     * Reads data from a binary string
     * 
     * @param string $binary a binary string
     * @return \Taakerman\Pipa\Image\Pipa a Pipa image
     * @throws Exception if an error occurs
     */
    function readMemory($binary);
    
    /**
     * Reads data from the filesystem (possibly a url)
     * 
     * @param string $path the path
     * @return \Taakerman\Pipa\Image\Pipa a Pipa image
     * @throws Exception if an error occurs
     */
    function readFile($path);
    
    /**
     * Writes a Pipa image to filesystem (possibly a url)
     * 
     * @param string $path the path to write to
     * @param array $options options for the driver
     * @param \Taakerman\Pipa\Image\Pipa $image the image to write to path
     * @throws Exception if an error occurs
     */
    function writeFile($path, Pipa $image, array $options);
}