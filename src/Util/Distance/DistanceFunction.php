<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Util\Distance;

/**
 * Provides a distance measure between 1d to nd points
 */
interface DistanceFunction {
    /**
     * The distance of 2 cartesian points in (n)d
     * 
     * @param mixed $p a number or an array of numbers
     * @param mixed $q a number or an array of numbers
     * @return float the distance
     */
    function cartesians($p, $q);
}
