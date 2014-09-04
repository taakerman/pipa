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

use Taakerman\Pipa\Analysis\HslHistogram;

use Taakerman\Pipa\PipaUnitTest;

class HslHistogramTest extends PipaUnitTest {
    private $H_HUE = array(1,4,2,10,31,81,111,139,298,696,799,794,1495,2575,4139,7054,10333,11289,11903,8510,5377,11049,46679,55568,51895,17829,1122,125,65,4,12,5,0,1,0,0,1,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,);
    private $H_SATURATION = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,2,5,11,18,13,53,105,393,2791,5092,3925,3049,2390,4276,2285,1180,1321,1262,1824,1784,829,1368,1226,1437,1827,1442,2229,1873,2812,2654,2644,3451,5046,5056,7218,7770,8400,12804,11813,7474,8865,10178,6358,3613,5196,5071,3733,1998,2526,3700,2048,2558,2469,954,1133,852,785,654,567,497,376,475,326,327,370,342,403,305,393,303,324,372,285,331,347,374,345,399,372,427,418,351,402,409,453,454,509,457,497,552,332,630,600,535,642,605,593,518,641,616,625,654,644,619,754,703,677,724,807,679,775,781,698,817,833,539,927,848,748,774,774,765,782,805,766,745,772,763,717,726,778,720,548,791,750,638,683,676,630,549,676,553,614,529,533,611,514,499,543,400,539,464,444,422,445,401,404,356,363,300,340,300,303,237,234,268,256,232,236,192,201,160,179,189,174,163,133,121,126,182,164,149,88,117,148,136,121,103,109,175,104,147,100,95,145,98,134,76,67,112,289,102,44,14,89,388,156,40,4,3,13,543,76,1,0,0,0,218,473,);
    private $H_LIGHTNESS = array(0,0,0,0,0,0,0,2,0,2,10,8,5,6,7,14,7,16,17,26,38,37,52,49,51,63,72,71,80,90,84,100,142,101,131,143,196,99,196,179,251,136,244,180,310,163,299,218,360,216,371,298,482,325,489,374,530,315,505,360,507,365,460,365,375,480,316,411,415,506,346,408,427,514,398,425,504,570,467,505,535,636,436,420,535,573,460,552,458,614,416,366,442,441,369,430,432,561,353,360,467,452,385,386,436,538,340,296,426,532,371,525,609,1017,503,446,705,915,910,1224,1129,1456,912,912,890,1353,1091,1168,1244,1182,1454,2109,445,1040,1160,1190,1162,1700,1379,3623,584,1878,1341,1079,1011,1138,1060,1675,626,1394,1307,1425,1602,1326,1342,1604,1546,1690,1965,2083,2016,2170,2371,3893,1784,2185,2294,2574,2589,2861,2834,2802,3487,4041,4681,5707,5225,5549,6323,7957,5752,6008,2943,7540,9001,6263,5551,5149,4068,3904,4416,4432,2364,1830,1289,1524,1954,1372,1163,1029,868,731,771,340,238,218,250,225,224,230,243,369,270,322,348,313,332,244,108,24,5,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,);

    public function testHslHistogram() {
        $image = $this->load('alyson.jpg');
        $this->assertNotNull($image);
        
        $hist = HslHistogram::calculate($image);
        $this->assertNotNull($hist);
        
        $this->assertEquals($this->H_HUE, $hist[0]);
        $this->assertEquals($this->H_SATURATION, $hist[1]);
        $this->assertEquals($this->H_LIGHTNESS, $hist[2]);

        //$this->histogramsToPhpArray($hist, 'hsl');
    }
    
    public function testChannels() {
        $image = $this->load('alyson.jpg');
        $this->assertNotNull($image);
        
        $hist = HslHistogram::calculateChannel($image, HslHistogram::CHANNEL_H);
        $this->assertEquals($this->H_HUE, $hist);
        
        $hist = HslHistogram::calculateChannel($image, HslHistogram::CHANNEL_S);
        $this->assertEquals($this->H_SATURATION, $hist);
        
        $hist = HslHistogram::calculateChannel($image, HslHistogram::CHANNEL_L);
        $this->assertEquals($this->H_LIGHTNESS, $hist);
    }
}
