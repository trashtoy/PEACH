<?php
class Peach_Util_DefaultComparatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Util_DefaultComparator
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Util_DefaultComparator::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * {@link Peach_Util_DefaultComparator::compare()} をテストします.
     * 以下を確認します.
     * 
     * - 一般的な大小比較
     * - string("2a") > int(2) となること (2a問題をクリアしていること)
     * - オブジェクト同士の比較では, var_dump の結果で大小比較が行われること
     * - Peach_Util_Comparable の実装オブジェクトの場合, compareTo の結果で代償比較が行われること
     * 
     * @covers Peach_Util_DefaultComparator::compare
     */
    public function testCompare()
    {
        $c = $this->object;
        $this->assertLessThan(   0, $c->compare(10,   20));
        $this->assertGreaterThan(0, $c->compare(25,   15.0));
        $this->assertSame(       0, $c->compare(10,   10));
        $this->assertSame(       0, $c->compare("3",  3));
        
        $this->assertGreaterThan(0, $c->compare("2a", 2));
        
        // Peach_Util_Comparable を実装していないオブジェクトの場合
        // var_dump の結果が適用されるため, 13 と 5 は文字列として比較される
        $obj1 = new Peach_Util_DefaultComparatorTest_Object(13, "hoge");
        $obj2 = new Peach_Util_DefaultComparatorTest_Object(5,  "asdf");
        $obj3 = new Peach_Util_DefaultComparatorTest_Object(5,  "fuga");
        $obj4 = new Peach_Util_DefaultComparatorTest_Object(13, "hoge");
        $this->assertLessThan(   0, $c->compare($obj1, $obj2));
        $this->assertSame(       0, $c->compare($obj1, $obj4));
        $this->assertLessThan(   0, $c->compare($obj2, $obj3));
        $this->assertGreaterThan(0, $c->compare($obj3, $obj1));
        
        // Peach_Util_Comparable を実装しているオブジェクトの場合
        // compareTo の結果が適用されるため, 80 と 120 は数値として比較される
        $c1   = new Peach_Util_DefaultComparatorTest_C("Tom",  80);
        $c2   = new Peach_Util_DefaultComparatorTest_C("Anna", 120);
        $c3   = new Peach_Util_DefaultComparatorTest_C("John", 120);
        $c4   = new Peach_Util_DefaultComparatorTest_C("Tom",  80);
        $this->assertLessThan(   0, $c->compare($c1, $c2));
        $this->assertLessThan(   0, $c->compare($c2, $c3));
        $this->assertSame(       0, $c->compare($c1, $c4));
        $this->assertGreaterThan(0, $c->compare($c3, $c1));
    }
    
    /**
     * {@link Peach_Util_DefaultComparator::getInstance()} のテストをします.
     * 以下を確認します.
     * 
     * - 返り値が Peach_Util_DefaultComparator のインスタンスである
     * - どの返り値も, 同一のインスタンスを返す
     * 
     * @covers Peach_Util_DefaultComparator::getInstance
     */
    public function testGetInstance()
    {
        $c1 = Peach_Util_DefaultComparator::getInstance();
        $c2 = Peach_Util_DefaultComparator::getInstance();
        $this->assertSame("Peach_Util_DefaultComparator", get_class($c1));
        $this->assertSame($c1, $c2);
    }
}

class Peach_Util_DefaultComparatorTest_Object
{
    private $id;
    private $name;
    
    public function __construct($id, $name)
    {
        $this->id   = $id;
        $this->name = $name;
    }
}

class Peach_Util_DefaultComparatorTest_C implements Peach_Util_Comparable
{
    private $name;
    private $value;
    
    public function __construct($name, $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }
    
    public function compareTo($subject)
    {
        if ($this->value !== $subject->value) {
            return $this->value - $subject->value;
        }
        
        return strcmp($this->name, $subject->name);
    }
}
?>