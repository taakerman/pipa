<?php

/*
 * THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY 
 * KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND/OR FITNESS FOR A
 * PARTICULAR PURPOSE. 
 * 
 * You may copy and reuse as you please
 */

namespace Taakerman\Pipa\Color;

use Taakerman\Pipa\Util\StringBuilder;
use Taakerman\Pipa\Color\Color;

use Taakerman\Pipa\PipaUnitTest;

use Exception;

class ColorTest extends PipaUnitTest {
    // colors from wikipedia 2014 http://en.wikipedia.org/wiki/HSL_and_HSV 
    private static $WIKI_COLORS = array(
                array('int' => 4294967295,'hex' => 'FFFFFF','hexa' => 'FFFFFFFF','rgb' => array(255,255,255),'rgba' => array(255,255,255,255),'hsv' => array(0,0,1),'hsl' => array(0,0,1),),
                array('int' => 4286611584,'hex' => '808080','hexa' => 'FF808080','rgb' => array(128,128,128),'rgba' => array(128,128,128,255),'hsv' => array(0,0,0.50196078431373),'hsl' => array(0,0,0.50196078431373),),
                array('int' => 4278190080,'hex' => '000000','hexa' => 'FF000000','rgb' => array(0,0,0),'rgba' => array(0,0,0,255),'hsv' => array(0,0,0),'hsl' => array(0,0,0),),
                array('int' => 4294901760,'hex' => 'FF0000','hexa' => 'FFFF0000','rgb' => array(255,0,0),'rgba' => array(255,0,0,255),'hsv' => array(0,1,1),'hsl' => array(0,1,0.5),),
                array('int' => 4290756352,'hex' => 'BFBF00','hexa' => 'FFBFBF00','rgb' => array(191,191,0),'rgba' => array(191,191,0,255),'hsv' => array(60,1,0.74901960784314),'hsl' => array(60,1,0.37450980392157),),
                array('int' => 4278222848,'hex' => '008000','hexa' => 'FF008000','rgb' => array(0,128,0),'rgba' => array(0,128,0,255),'hsv' => array(120,1,0.50196078431373),'hsl' => array(120,1,0.25098039215686),),
                array('int' => 4286644223,'hex' => '80FFFF','hexa' => 'FF80FFFF','rgb' => array(128,255,255),'rgba' => array(128,255,255,255),'hsv' => array(180,0.49803921568627,1),'hsl' => array(180,1,0.75098039215686),),
                array('int' => 4286611711,'hex' => '8080FF','hexa' => 'FF8080FF','rgb' => array(128,128,255),'rgba' => array(128,128,255,255),'hsv' => array(240,0.49803921568627,1),'hsl' => array(240,1,0.75098039215686),),
                array('int' => 4290724031,'hex' => 'BF40BF','hexa' => 'FFBF40BF','rgb' => array(191,64,191),'rgba' => array(191,64,191,255),'hsv' => array(300,0.66492146596859,0.74901960784314),'hsl' => array(300,0.49803921568627,0.5),),
                array('int' => 4288717860,'hex' => 'A0A424','hexa' => 'FFA0A424','rgb' => array(160,164,36),'rgba' => array(160,164,36,255),'hsv' => array(61.875,0.78048780487805,0.64313725490196),'hsl' => array(61.875,0.64,0.3921568627451),),
                array('int' => 4282457066,'hex' => '411BEA','hexa' => 'FF411BEA','rgb' => array(65,27,234),'rgba' => array(65,27,234,255),'hsv' => array(251.01449275362,0.88461538461538,0.91764705882353),'hsl' => array(251.01449275362,0.83132530120482,0.51176470588235),),
                array('int' => 4280200257,'hex' => '1EAC41','hexa' => 'FF1EAC41','rgb' => array(30,172,65),'rgba' => array(30,172,65,255),'hsv' => array(134.78873239437,0.82558139534884,0.67450980392157),'hsl' => array(134.78873239437,0.7029702970297,0.39607843137255),),
                array('int' => 4293969934,'hex' => 'F0C80E','hexa' => 'FFF0C80E','rgb' => array(240,200,14),'rgba' => array(240,200,14,255),'hsv' => array(49.380530973451,0.94166666666667,0.94117647058824),'hsl' => array(49.380530973451,0.88976377952756,0.49803921568627),),
                array('int' => 4289999077,'hex' => 'B430E5','hexa' => 'FFB430E5','rgb' => array(180,48,229),'rgba' => array(180,48,229,255),'hsv' => array(283.75690607735,0.79039301310044,0.89803921568627),'hsl' => array(283.75690607735,0.77682403433476,0.54313725490196),),
                array('int' => 4293752401,'hex' => 'ED7651','hexa' => 'FFED7651','rgb' => array(237,118,81),'rgba' => array(237,118,81,255),'hsv' => array(14.230769230769,0.65822784810127,0.92941176470588),'hsl' => array(14.230769230769,0.8125,0.62352941176471),),
                array('int' => 4294899848,'hex' => 'FEF888','hexa' => 'FFFEF888','rgb' => array(254,248,136),'rgba' => array(254,248,136,255),'hsv' => array(56.949152542373,0.46456692913386,0.99607843137255),'hsl' => array(56.949152542373,0.98333333333333,0.76470588235294),),
                array('int' => 4279880599,'hex' => '19CB97','hexa' => 'FF19CB97','rgb' => array(25,203,151),'rgba' => array(25,203,151,255),'hsv' => array(162.47191011236,0.87684729064039,0.79607843137255),'hsl' => array(162.47191011236,0.78070175438596,0.44705882352941),),
                array('int' => 4281738904,'hex' => '362698','hexa' => 'FF362698','rgb' => array(54,38,152),'rgba' => array(54,38,152,255),'hsv' => array(248.42105263158,0.75,0.59607843137255),'hsl' => array(248.42105263158,0.6,0.37254901960784),),
                array('int' => 4286480056,'hex' => '7E7EB8','hexa' => 'FF7E7EB8','rgb' => array(126,126,184),'rgba' => array(126,126,184,255),'hsv' => array(240,0.31521739130435,0.72156862745098),'hsl' => array(240,0.29,0.6078431372549),),
    );
    
    private function assertColorEquality(array $arr) { 
        $int = $arr['int'];
        $hex = $arr['hex'];
        $hexa = $arr['hexa'];
        $rgb = $arr['rgb'];
        $rgba = $arr['rgba'];
        $hsv = $arr['hsv'];
        $hsl = $arr['hsl'];

        foreach ($arr as $k1 => $v1) {
            $color = null;
            switch ($k1) {
                case 'int':
                    $color = Color::fromInt($v1);
                    break;
                case 'hex':
                    $color = Color::fromHex($v1);
                    break;
                case 'hexa':
                    $color = Color::fromHexa($v1);
                    break;
                case 'rgb':
                    $color = Color::fromRgb($v1);
                    break;
                case 'rgba':
                    $color = Color::fromRgba($v1);
                    break;
                case 'hsv':
                    $color = Color::fromHsv($v1);
                    break;
                case 'hsl':
                    $color = Color::fromHsl($v1);
                    break;
                default:
                    throw new Exception('Unknown format: ' . $k1 . ' for hex ' . $hex);
            }
            
            // test against all other formats
            foreach ($arr as $k2 => $v2) {
                switch ($k2) {
                    case 'int':
                        $this->assertEquals($v2, $color->toInt(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'hex':
                        $this->assertEquals($v2, $color->toHex(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'hexa':
                        $this->assertEquals($v2, $color->toHexa(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'rgb':
                        $this->assertEquals($v2, $color->toRgb(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'rgba':
                        $this->assertEquals($v2, $color->toRgba(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'hsv':
                        $this->assertEquals($v2, $color->toHsv(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    case 'hsl':
                        $this->assertEquals($v2, $color->toHsl(), "$hex: $k1 => $k2", self::DELTA);
                        break;
                    default:
                        throw new Exception('Unknown format: ' . $k2 . ' for hex ' . $hex);
                }
            }
        }
        
    }
    
    public function testWikiColor() {
        foreach (self::$WIKI_COLORS as $color) {
            $this->assertColorEquality($color);
        }
    }
    
    public function testImageColor() {
        // similar test as assertColorEquality, just written out
        $int = 4291667715;
        $hex = 'CDA703';
        $hexa = 'FFCDA703';
        $rgb = array(205, 167, 3);
        $rgba = array(205, 167, 3, 255);
        $hsv = array(48.712871287129, 0.98536585365854, 0.80392156862745);
        $hsl = array(48.712871287129, 0.97115384615385, 0.4078431372549);


        // to int
        $this->assertEquals($int, Color::fromInt($int)->toInt(), "#$hex: int => int", self::DELTA);
        $this->assertEquals($int, Color::fromHex($hex)->toInt(), "#$hex: hex => int", self::DELTA);
        $this->assertEquals($int, Color::fromHexa($hexa)->toInt(), "#$hex: hexa => int", self::DELTA);
        $this->assertEquals($int, Color::fromRgb($rgb)->toInt(), "#$hex: rgb => int", self::DELTA);
        $this->assertEquals($int, Color::fromRgba($rgba)->toInt(), "#$hex: rgba => int", self::DELTA);
        $this->assertEquals($int, Color::fromHsv($hsv)->toInt(), "#$hex: hsv => int", self::DELTA);
        $this->assertEquals($int, Color::fromHsl($hsl)->toInt(), "#$hex: hsl => int", self::DELTA);
        
        // to hex
        $this->assertEquals($hex, Color::fromInt($int)->toHex(), "#$hex: int => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromHex($hex)->toHex(), "#$hex: hex => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromHexa($hexa)->toHex(), "#$hex: hexa => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromRgb($rgb)->toHex(), "#$hex: rgb => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromRgba($rgba)->toHex(), "#$hex: rgba => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromHsv($hsv)->toHex(), "#$hex: hsv => hex", self::DELTA);
        $this->assertEquals($hex, Color::fromHsl($hsl)->toHex(), "#$hex: hsl => hex", self::DELTA);
        
        // to hexa
        $this->assertEquals($hexa, Color::fromInt($int)->toHexa(), "#$hex: int => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromHex($hex)->toHexa(), "#$hex: hex => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromHexa($hexa)->toHexa(), "#$hex: hexa => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromRgb($rgb)->toHexa(), "#$hex: rgb => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromRgba($rgba)->toHexa(), "#$hex: rgba => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromHsv($hsv)->toHexa(), "#$hex: hsv => hexa", self::DELTA);
        $this->assertEquals($hexa, Color::fromHsl($hsl)->toHexa(), "#$hex: hsl => hexa", self::DELTA);
        
        // to rgb
        $this->assertEquals($rgb, Color::fromInt($int)->toRgb(), "#$hex: int => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromHex($hex)->toRgb(), "#$hex: hex => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromHexa($hexa)->toRgb(), "#$hex: hexa => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromRgb($rgb)->toRgb(), "#$hex: rgb => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromRgba($rgba)->toRgb(), "#$hex: rgba => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromHsv($hsv)->toRgb(), "#$hex: hsv => rgb", self::DELTA);
        $this->assertEquals($rgb, Color::fromHsl($hsl)->toRgb(), "#$hex: hsl => rgb", self::DELTA);
        
        // to rgba
        $this->assertEquals($rgba, Color::fromInt($int)->toRgba(), "#$hex: int => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromHex($hex)->toRgba(), "#$hex: hex => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromHexa($hexa)->toRgba(), "#$hex: hexa => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromRgb($rgb)->toRgba(), "#$hex: rgb => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromRgba($rgba)->toRgba(), "#$hex: rgba => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromHsv($hsv)->toRgba(), "#$hex: hsv => rgba", self::DELTA);
        $this->assertEquals($rgba, Color::fromHsl($hsl)->toRgba(), "#$hex: hsl => rgba", self::DELTA);

        // to hsv
        $this->assertEquals($hsv, Color::fromInt($int)->toHsv(), "#$hex: int => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromHex($hex)->toHsv(), "#$hex: hex => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromHexa($hexa)->toHsv(), "#$hex: hexa => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromRgb($rgb)->toHsv(), "#$hex: rgb => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromRgba($rgba)->toHsv(), "#$hex: rgba => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromHsv($hsv)->toHsv(), "#$hex: hsv => hsv", self::DELTA);
        $this->assertEquals($hsv, Color::fromHsl($hsl)->toHsv(), "#$hex: hsl => hsv", self::DELTA);
        
        // to hsv
        $this->assertEquals($hsl, Color::fromInt($int)->toHsl(), "#$hex: int => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromHex($hex)->toHsl(), "#$hex: hex => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromHexa($hexa)->toHsl(), "#$hex: hexa => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromRgb($rgb)->toHsl(), "#$hex: rgb => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromRgba($rgba)->toHsl(), "#$hex: rgba => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromHsv($hsv)->toHsl(), "#$hex: hsv => hsl", self::DELTA);
        $this->assertEquals($hsl, Color::fromHsl($hsl)->toHsl(), "#$hex: hsl => hsl", self::DELTA);
    }
    
    public function testHexadecimalExpansion() {
        $hex = '#F5A';
        $hexa = '#FF5A';
        
        $this->assertEquals('FF55AA', Color::fromHex($hex)->toHex());
        $this->assertEquals('FFFF55AA', Color::fromHexa($hexa)->toHexa());
    }
    
    public function testHexadecimalLowercase() {
        $hex = '#f5a';
        $hexa = '#ff5a';
        
        $this->assertEquals('FF55AA', Color::fromHex($hex)->toHex());
        $this->assertEquals('FFFF55AA', Color::fromHexa($hexa)->toHexa());
    }
    
    private function createColorArray($hex) {
        $c = Color::fromHex($hex);
        
        $sb = new StringBuilder();
        $sb->append('array(');
        $sb->append("'int' => " . $c->toInt());
        $sb->append(',');
        $sb->append("'hex' => '" . $c->toHex() . "',");
        $sb->append("'hexa' => '" . $c->toHexa() . "',");
        list($r, $g, $b) = $c->toRgb();
        $sb->append("'rgb' => array($r,$g,$b),");
        list($r, $g, $b, $a) = $c->toRgba();
        $sb->append("'rgba' => array($r,$g,$b,$a),");
        list($h, $s, $v) = $c->toHsv();
        $sb->append("'hsv' => array($h,$s,$v),");
        list($h, $s, $l) = $c->toHsl();
        $sb->append("'hsl' => array($h,$s,$l),");
        
        $sb->append('),');
        
        print $sb->build() . "\n";
    }
}
