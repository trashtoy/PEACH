<?php
class Peach_Util_DefaultEquatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Util_DefaultEquator
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Util_DefaultEquator::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {  
    }
    
    /**
     * @covers Peach_Util_DefaultEquator::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_Util_DefaultEquator::getInstance();
        $obj2 = Peach_Util_DefaultEquator::getInstance();
        $this->assertSame("Peach_Util_DefaultEquator", get_class($obj1));
        $this->assertTrue($obj1 === $obj2);
    }

    /**
     * @covers Peach_Util_DefaultEquator::equate
     */
    public function testEquate()
    {
        $e    = $this->object;
        $obj1 = new Peach_Util_DefaultEquatorTest_Object(15);
        $obj2 = new Peach_Util_DefaultEquatorTest_Object(20);
        $obj3 = new Peach_Util_DefaultEquatorTest_Object(15);
        $arr1 = array("a" => 10, "b" => 15, "c" => 13);
        $arr2 = array("a" => 10, "b" => 15, "c" => 12);
        $arr3 = array("a" => 10, "b" => 15, "c" => 12);
        $this->assertTrue($e->equate(-10, -10.0));
        $this->assertTrue($e->equate("15", 15));
        $this->assertFalse($e->equate("2a", 2));
        $this->assertFalse($e->equate(true, 1));
        $this->assertFalse($e->equate($obj1, $obj2));
        $this->assertTrue($e->equate($obj1,  $obj3));
        $this->assertFalse($e->equate($arr1, $arr2));
        $this->assertTrue($e->equate($arr2,  $arr3));
    }

    /**
     * @covers Peach_Util_DefaultEquator::hashCode
     */
    public function testHashCode()
    {
        $e = $this->object;
        $this->assertSame(0,  $e->hashCode(null));
        $this->assertSame(0,  $e->hashCode(false));
        $this->assertSame(0,  $e->hashCode(array()));
        $this->assertSame(10, $e->hashCode(10));
        $this->assertSame(20, $e->hashCode("0020"));
        $test1 = new Peach_Util_DefaultEquatorTest_Object(123);
        $test2 = new Peach_Util_DefaultEquatorTest_Object(123);
        $hash1 = $e->hashCode($test1);
        $hash2 = $e->hashCode($test2);
        $this->assertSame($hash1, $hash2);
    }
}

class Peach_Util_DefaultEquatorTest_Object
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
}
?>