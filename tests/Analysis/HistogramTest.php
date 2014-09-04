<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Analysis;

use Taakerman\Pipa\Analysis\Histogram;

use Taakerman\Pipa\PipaUnitTest;

class HistogramTest extends PipaUnitTest {
    private static $HIST = array(0,0,0,12,15,68,133,159,447,174,549,322,195,332,254,265,266,215,163,185,143,178,210,183,226,226,266,279,378,411,196,372,371,241,245,227,234,207,199,217,229,216,206,215,258,199,200,196,221,206,207,183,201,183,185,239,243,184,203,231,270,532,487,287,312,798,266,808,660,283,218,205,199,182,214,209,169,206,222,207,214,203,223,237,212,238,243,230,247,240,236,252,296,271,319,298,360,302,309,286,329,315,288,354,339,321,332,332,303,357,339,333,354,326,317,377,308,366,329,373,352,298,332,343,342,358,330,310,357,345,327,327,310,383,313,299,311,305,296,265,285,233,287,240,274,248,257,260,230,237,229,216,220,219,183,204,229,211,214,233,218,236,203,203,174,176,199,157,190,174,153,144,136,142,143,130,147,148,125,101,108,95,96,78,90,55,69,75,68,48,38,38,27,20,17,19,9,6,10,8,7,4,7,3,7,4,0,3,6,2,3,2,1,3,1,1,1,2,0,2,0,2,1,1,2,1,1,0,2,0,1,3,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,);

    public function testHistogram() {
        $image = $this->load('DSC_3195.jpg');
        $this->assertNotNull($image);
        
        $hist = Histogram::calculate($image);
        $this->assertNotNull($hist);
        $this->assertEquals(self::$HIST, $hist);
        
        //$visualization = Visualizer::visualizeHistogram($histogram);
        //$visualization->save(__DIR__ . '/histogram4.png');
    }
}