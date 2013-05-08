<?php
require_once dirname(__FILE__) . '/../../src/Util/load.php';

class Util_DefaultEquatorTest extends PHPUnit_Framework_TestCase
{
    private $e;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->e = Util_DefaultEquator::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    public function testGetInstance()
    {
        $obj1 = Util_DefaultEquator::getInstance();
        $obj2 = Util_DefaultEquator::getInstance();
        $this->assertTrue($obj1 === $obj2);
    }
    
    public function testEquate()
    {
        $e    = $this->e;
        $obj1 = new Test(15);
        $obj2 = new Test(20);
        $obj3 = new Test(15);
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
    
    public function testHashCode()
    {
        $e = $this->e;
        $this->assertSame(0,  $e->hashCode(null));
        $this->assertSame(0,  $e->hashCode(false));
        $this->assertSame(0,  $e->hashCode(array()));
        $this->assertSame(10, $e->hashCode(10));
        $this->assertSame(20, $e->hashCode("0020"));
        $test1 = new Test(123);
        $test2 = new Test(123);
        $hash1 = $e->hashCode($test1);
        $hash2 = $e->hashCode($test2);
        $this->assertSame($hash1, $hash2);
    }
}

class Test
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
}
?>