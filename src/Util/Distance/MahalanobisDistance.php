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

use Taakerman\Pipa\Util\Distance\DistanceFunction;
use Malenki\Math\Matrix;

// http://www.vinc.be/www-pdf/10-11_SINF2356-Mahalanobis-JaroWinckler-nDollar.pdf
//
// Du vil nok gerne lave Cx udfra paletten
// Derefter vil du teste hvert enkelt X med hver enkelt farve i paletten xi
// dvs (X - Xi) * Cx-1 * (X -Xi)
//
class MahalanobisDistance implements DistanceFunction {
    /**
     * $m = new Matrix(4, 3);
     * $m->populate(array(
     *     1, 5, 9, 
     *     2, 6, 0,
     *     3, 7, 1, 
     *     4,8,2
     * ));
     * 
     * 
     *   x1  x2  x3
     * 1  1   5   9
     * 2  2   6   0
     * 3  3   7   1
     * 4  4   8   2
     * 
     * based on {@link http://nyx-www.informatik.uni-bremen.de/1021/1/maesschalck_jouan-rimbaud_mahalanobis_00.pdf}
     * @param type $data array where columns are variables and rows are observations
     */
    public static function create(Matrix $observations) {
        // todo, re-check this and use wiki source instead!
        $cols = $observations->cols;
        $rows = $observations->rows;
        $centered = new Matrix($rows, $cols);
        
        for ($c = 0; $c < $cols; ++$c) {
            // calculate mean of column
            $mean = sum($observations->getCol($c)) / $rows;

            // for each row
            for ($r = 0; $r < $rows; ++$r) {
                // calculate the centered xi
                $xi = $observations->get($r, $c);
                $center = $xi - $mean;
                $centered->set($r, $c, $center);
            }
        }
        
        // precalculate the transpose of the centered xi
        $cT = $centered->transpose();
        
        // calculate Cx inverse
        $CxInv = $centered->multiply($cT)
                ->multiply( 1 / ($rows - 1))
                ->inverse();
        
        // calculate squared mahalanobis
        $md2 = $centered->multiply($CxInv)
                ->multiply($cT);
        
        // return distance
        return sqrt($md2);
    }

    public function cartesians(array $p, array $q) {
        
    }

}