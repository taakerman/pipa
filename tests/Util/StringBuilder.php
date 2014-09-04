<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util;

/**
 * A simple string building class, used to output stuff while testing
 */
class StringBuilder {
    const NEWLINE = "\n";
    const TAB = "\t";
    
    private $buf = '';
    
    public function __construct() {
        
    }
    
    public function append($str) {
        $this->buf .= $str;
        return $this;
    }
    
    public function nl() {
        $this->append(self::NEWLINE);
        return $this;
    }
    
    public function tab() {
        $this->append(self::TAB);
        return $this;
    }
    
    public function appendln($str) {
        $this->append($str);
        $this->nl();
        return $this;
    }
    
    public function appendf($format) {
        $args = func_get_args();
        unset($args[0]);
        $this->append(vsprintf($format, $args));
        return $this;
    }
    
    
    public function appendfln($format) {
        $args = func_get_args();
        unset($args[0]);
        $this->append(vsprintf($format, $args));
        $this->nl();
        return $this;
    }
    
    public function build() {
        return $this->buf;
    }
}
