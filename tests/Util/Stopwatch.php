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
 * A stopwatch, to benchmark running tasks
 */
class Stopwatch {
    const MS = 0;
    const SEC = 1;
    const MIN = 2;
    const HOUR = 4;
    
    private $running;
    private $start;
    private $stop;
    
    /**
     * Create a stopwatch, not started yet
     */
    public function __construct() {
        $this->running = false;
    }
    
    /**
     * Creates and starts a stopwatch
     * 
     * @return \Taakerman\Pipa\Util\Stopwatch
     */
    public static function createStarted() {
        $sw = new Stopwatch();
        $sw->start();
        return $sw;
    }
    
    /**
     * Starts the stopwatch
     * 
     * @return \Taakerman\Pipa\Util\Stopwatch
     */
    public function start() {
        if (!$this->running) {
            $this->start = $this->now();
            $this->running = true;
        }
        
        return $this;
    }
    
    /**
     * Stops the stopwatch
     * 
     * @return \Taakerman\Pipa\Util\Stopwatch
     */
    public function stop() {
        if ($this->running) {
            $this->stop = $this->now();
            $this->running = false;
        }
        
        return $this;
    }
    
    /**
     * Returns the current MS
     * 
     * @return float
     */
    private function now() {
        return microtime(true) * 1000.0;
    }
    
    /**
     * Returns the time elapsed, also when the stopwatch is running
     * 
     * @param int $timeUnit the timeunit to get time in (use one of the class constants)
     * @return float the elapsed time
     */
    public function elapsed($timeUnit = self::MS) {
        if (!$this->running) {
            // if stopped
            $elapsedMS = $this->stop - $this->start;
        } else {
            // if running
            $elapsedMS = $this->now() - $this->start;
        }
        
        switch ($timeUnit) {
            case self::SEC:
                return (int) ceil($elapsedMS / 1000.0);
            case self::MIN:
                return (int) ceil(($elapsedMS / 1000.0) / 60.0);
            case self::HOUR:
                return (int) ceil((($elapsedMS / 1000.0) / 60.0) / 60.0);
            default:
                return (int) ceil($elapsedMS);
        }
    }
    
    /**
     * Returns time as HH:mm:ss.zzzz
     * 
     * @param float $time the time to format
     * @param int $timeUnit describes the units of $time (use one of the time constants in this class)
     */
    public static function formatTime($time, $timeUnit = self::MS) {
        
    }
}