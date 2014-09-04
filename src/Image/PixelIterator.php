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
use Taakerman\Pipa\Image\Pixel;
use Iterator;

/**
 * An iterator to iterate over an images pixels
 */
class PixelIterator implements Iterator {
    private $pos = 0;
    private $w = 0;
    private $h = 0;
    /* @var $image \Taakerman\Pipa\Image\Pipa */
    private $image;
    
    /**
     * Constructs a new iterator based on an image
     * 
     * @param \Taakerman\Pipa\Image\Pipa $image the image to create iterator for
     */
    function __construct(Pipa &$image) {
        $this->image = $image;
        $this->w = $image->getWidth();
        $this->h = $image->getHeight();
    }

    public function current() {
        $y = (int) ($this->pos / $this->w);
        $x = (int) ($this->pos % $this->w);
        
        return new Pixel($x, $y, $this->image->getPixel($x, $y));
    }

    public function key() {
        return $this->pos;
    }

    public function next() {
        ++$this->pos;
    }

    public function rewind() {
        $this->pos = 0;
    }

    public function valid() {
        return $this->pos < $this->w * $this->h;
    }
}
