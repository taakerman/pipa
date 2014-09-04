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

use Taakerman\Pipa\Transform\Interpolate\NearestNeighbor;
use Taakerman\Pipa\PipaUnitTest;

class NearestNeighborTest extends PipaUnitTest {
    private $nnScaled = array(
            array(0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, 0xff800080, 0xff800080, 0xff800080, 0xff800080, ),
            array(0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, 0xff800080, 0xff800080, 0xff800080, 0xff800080, ),
            array(0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, 0xff800080, 0xff800080, ),
            array(0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, 0xff800080, 0xff800080, ),
            array(0xff804000, 0xff804000, 0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, ),
            array(0xff804000, 0xff804000, 0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, 0xff00ff80, 0xff00ff80, ),
            array(0xff804000, 0xff804000, 0xff804000, 0xff804000, 0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, ),
            array(0xff804000, 0xff804000, 0xff804000, 0xff804000, 0xffffff00, 0xffffff00, 0xffff0000, 0xffff0000, ),
    );
    
    private $alysonScaled = array(
            array(0xffc8b49c, 0xffc4b098, 0xffc5b198, 0xffc5b199, 0xffcfbda7, 0xffd3c3ac, 0xffcbb9a1, 0xffc3ad95, ),
            array(0xffcdbba5, 0xffccbaa2, 0xffc9b59d, 0xffc6b299, 0xffd0bea6, 0xffd3c1a9, 0xffccb89f, 0xffc1ab93, ),
            array(0xffccbaa2, 0xffccbaa2, 0xffba997a, 0xffc58d5e, 0xffc3af94, 0xffcebda3, 0xffc6ae94, 0xffcdb599, ),
            array(0xffd0bea6, 0xffac6434, 0xff8c4b21, 0xff994c1e, 0xff5a2b11, 0xffcdb599, 0xffc1ad8c, 0xffbfa688, ),
            array(0xffcebca4, 0xff955d3c, 0xfff8c6a3, 0xffd38b63, 0xffdc9d71, 0xffcb8d5e, 0xffcbb196, 0xffc9b497, ),
            array(0xffd0bea6, 0xffc9b195, 0xff8b441a, 0xffd39065, 0xff7e5c43, 0xffb59e7c, 0xffdfad7a, 0xffc0ab8e, ),
            array(0xffc8b69e, 0xffcbb9a1, 0xffcdbba3, 0xffd5bda1, 0xffdccbb7, 0xffd7c7b0, 0xffcebda3, 0xffc5b095, ),
            array(0xffd8c8b1, 0xffccbaa2, 0xffbca68e, 0xffb19981, 0xffa28c74, 0xff8a765e, 0xff957f67, 0xff9c866e, ),
    );
    
    public function testScale() {
        $image = $this->load('nn-64x64.png');
        $scaledImage = NearestNeighbor::interpolate($image, 8, 8);
        $this->assertImageArrayEquals($this->nnScaled, $scaledImage);
    }
    
    public function testAlysonScale() {
        $image = $this->load('alyson.jpg');
        $scaledImage = NearestNeighbor::interpolate($image, 8, 8);
        $this->assertImageArrayEquals($this->alysonScaled, $scaledImage);
    }
}
