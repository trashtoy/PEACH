<?php
require_once dirname(__FILE__) . '/../../src/Util/load.php';

class Util_ArraysTest extends PHPUnit_Framework_TestCase
{
    private static $fp;
    
    public static function setUpBeforeClass()
    {
        self::$fp = fopen(__FILE__, "rb");
    }
    
    public static function tearDownAfterClass()
    {
        fclose(self::$fp);
    }
    
    public function testMax()
    {
        $test = array(5, 1, 3, 10, -10);
        $this->assertSame(10, Util_Arrays::max($test));
        
        $test = array();
        $test[] = new Util_ArraysTest_Comparable(150, "ABC");
        $test[] = new Util_ArraysTest_Comparable(100, "XYZ");
        $test[] = new Util_ArraysTest_Comparable(150, "DEF");
        $test[] = new Util_ArraysTest_Comparable(125, "MNG");
        $this->assertEquals($test[2], Util_Arrays::max($test));
    }
    
    public function testMin()
    {
        $test = array(5, 1, 3, 10, -10);
        $this->assertSame(-10, Util_Arrays::min($test));
        
        $test = array();
        $test[] = new Util_ArraysTest_Comparable(150, "ABC");
        $test[] = new Util_ArraysTest_Comparable(100, "XYZ");
        $test[] = new Util_ArraysTest_Comparable(150, "DEF");
        $test[] = new Util_ArraysTest_Comparable(125, "MNG");
        $this->assertEquals($test[1], Util_Arrays::min($test));
    }
    
    public function testPickup()
    {
        $obj1 = new Util_ArraysTest_Object("Hoge", 100);
        $obj2 = new Util_ArraysTest_Object("Fuga", 200);
        $arr = array(
            "A",
            1,
            null,
            array(),
            $obj1,
            "B",
            true,
            $obj2,
            2.5,
            array(1, 3, 5),
            null,
            false,
            self::$fp
        );
        
        $expected = array("A", "B");
        $this->assertSame($expected, Util_Arrays::pickup($arr, "string"));
        
        $expected = array(1, 2.5);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "numeric"));
        $this->assertSame($expected, Util_Arrays::pickup($arr, "float"));
        
        $expected = array(1);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "INT"));
        $this->assertSame($expected, Util_Arrays::pickup($arr, "integer"));
        
        $expected = array(true, false);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "bool"));
        $this->assertSame($expected, Util_Arrays::pickup($arr, "Boolean"));
        
        $expected = array(self::$fp);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "resource"));
        
        $expected = array(null, null);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "null"));
        
        $expected = array(array(), array(1, 3, 5));
        $this->assertSame($expected, Util_Arrays::pickup($arr, "array"));
        
        $expected = array($obj1, $obj2);
        $this->assertSame($expected, Util_Arrays::pickup($arr, "object"));
        $this->assertSame($expected, Util_Arrays::pickup($arr, "Util_ArraysTest_Object"));
        
        $expected = array();
        $this->assertSame($expected, Util_Arrays::pickup($arr, "Unknown_Object"));
        
        // 第3引数を TRUE にした場合は添字を維持する
        $expected = array(0 => "A", 1 => "B");
        $this->assertSame($expected, Util_Arrays::pickup($arr, "string", false));
        $expected = array(0 => "A", 5 => "B");
        $this->assertSame($expected, Util_Arrays::pickup($arr, "string", true));
    }
    
    public function testSort()
    {
        // 空の配列をソートした場合は空の配列を返す
        $result = Util_Arrays::sort(array());
        $this->assertSame(array(), $result);
        
        // 要素数が 1 の配列はそのままとなる
        $result = Util_Arrays::sort(array(5));
        $this->assertSame(array(5), $result);
        
        // 要素数が 2 の場合のテスト
        $result = Util_Arrays::sort(array(20, 10));
        $this->assertSame(array(10, 20), $result);
        
        // 通常のテストデータでソート
        $expected = array_values(self::getSampleArray());
        
        $subject  = self::getSampleArray();
        $result   = Util_Arrays::sort($subject);
        $this->assertSame($expected, $result);
        
        $subject  = self::getSampleReverseArray();
        $result   = Util_Arrays::sort($subject);
        $this->assertSame($expected, $result);
        
        $subject  = self::getSampleShuffleArray();
        $result   = Util_Arrays::sort($subject);
        $this->assertSame($expected, $result);
    }
    
    public function testAsort()
    {
        // 空の配列をソートした場合は空の配列を返す
        $result = Util_Arrays::asort(array());
        $this->assertSame(array(), $result);
        
        // 要素数が 1 の配列はそのままとなる
        $result = Util_Arrays::asort(array("foo" => "asdf"));
        $this->assertSame(array("foo" => "asdf"), $result);
        
        // 要素数が 2 の場合のテスト
        $result = Util_Arrays::asort(array("hoge" => 5, "hogu" => 3));
        $this->assertSame(array("hogu" => 3, "hoge" => 5), $result);
        
        // 通常のテストデータでソート
        $expected = self::getSampleArray();
        
        $subject  = self::getSampleArray();
        $result   = Util_Arrays::asort($subject);
        $this->assertSame($expected, $result);
        
        $subject  = self::getSampleReverseArray();
        $result   = Util_Arrays::asort($subject);
        $this->assertSame($expected, $result);
        
        $subject  = self::getSampleShuffleArray();
        $result   = Util_Arrays::asort($subject);
        $this->assertSame($expected, $result);
    }
    
    public function testConcat()
    {
        $result = Util_Arrays::concat();
        $this->assertSame(array(), $result);
        
        $result = Util_Arrays::concat(100, 200, 400, 800);
        $this->assertSame(array(100, 200, 400, 800), $result);
        
        $arr1 = array(1, 2, 3);
        $arr2 = array(5, 7);
        $result   = Util_Arrays::concat($arr1, "X", $arr2, "Y");
        $expected = array(1, 2, 3, "X", 5, 7, "Y");
        $this->assertSame($expected, $result);
        
        $subarr1 = array(10, 20);
        $subarr2 = array(30, 50, 70);
        $arr3    = array($subarr1, $subarr2);
        $result  = Util_Arrays::concat($arr1, $arr3);
        $expected = array(1, 2, 3, $subarr1, $subarr2);
        $this->assertSame($expected, $result);
    }
    
    public function testUnique()
    {
        $test     = array(1, 3, "2", 7, 5, 0, 8, "3", 2, 9, 3, 6, "5", 4);
        $expected = array(0 => 1, 1 => 3, 2 => "2", 3 => 7, 4 => 5, 5 => 0, 6 => 8, 9 => 9, 11 => 6, 13 => 4);
        $result   = Util_Arrays::unique($test);
        $this->assertSame($expected, $result);
    }
    
    private static function getSampleArray()
    {
        static $arr;
        if (!isset($arr)) {
            $arr = array(
                "the"   => 1,
                "quick" => 2,
                "brown" => 3,
                "fox"   => 4,
                "jumps" => 8,
                "over"  => 12,
                "lazy"  => 16,
                "dogs"  => 20
            );
        }
        return $arr;
    }
    
    private static function getSampleReverseArray()
    {
        $arr = self::getSampleArray();
        return array_reverse($arr);
    }
    
    private static function getSampleShuffleArray()
    {
        $arr  = self::getSampleArray();
        $keys = array_keys($arr);
        $vals = array_values($arr);
        $nums = range(0, 7);
        $test = array();
        shuffle($nums);
        foreach ($nums as $i) {
            $key = $keys[$i];
            $val = $vals[$i];
            $test[$key] = $val;
        }
        return $test;
    }
}

class Util_ArraysTest_Object implements Util_Comparable
{
    private $name;
    private $value;
    
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
    
    public function compareTo($subject)
    {
        $cmp = strcmp($this->name, $subject->name);
        return $cmp ? $cmp : $this->value - $subject->value;
    }
    
    public function __toString()
    {
        return $this->name . ":" . $this->value;
    }
}

class Util_ArraysTest_Comparable implements Util_Comparable
{
    private $var1;
    private $var2;
    
    public function __construct($var1, $var2)
    {
        $this->var1 = $var1;
        $this->var2 = $var2;
    }
    
    public function compareTo($subject)
    {
        if ($subject instanceof Util_ArraysTest_Comparable) {
            $comp = $this->var1 - $subject->var1;
            return $comp === 0 ? strcmp($this->var2, $subject->var2) : $comp;
        } else {
            throw new Exception();
        }
    }
}
?>