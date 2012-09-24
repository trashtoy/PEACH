<?php
require_once dirname(__FILE__) . '/../../src/Util/load.php';

class Util_DefaultComparatorTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Util_DefaultComparator
     */
    private $c;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->c = Util_DefaultComparator::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * @todo Implement testCompare().
     */
    public function testCompare() {
        $c = $this->c;
        $this->assertLessThan(0,    $c->compare(10, 20));
        $this->assertGreaterThan(0, $c->compare(25, 15.0));
        $this->assertSame(0,        $c->compare(10, 10));
        $this->assertSame(0,        $c->compare("3", 3));
        $this->assertGreaterThan(0, $c->compare("5a", 5));
        $obj1 = new TestC(5, "hoge");
        $obj2 = new TestC(7, "asdf");
        $obj3 = new TestC(7, "fuga");
        $obj4 = new TestC(5, "hoge");
    }
    
    public function testGetInstance() {
        $c1 = Util_DefaultComparator::getInstance();
        $c2 = Util_DefaultComparator::getInstance();
        $this->assertSame("Util_DefaultComparator", get_class($c1));
        $this->assertSame(TRUE, $c1 === $c2);
    }
}
class TestObject {
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }
}
class TestC implements Util_Comparable {
    private $value1;
    private $value2;
    public function __construct($value1, $value2) {
        $this->value1 = $value1;
        $this->value2 = $value2;
    }
    
    public function compareTo($subject) {
        if ($subject instanceof TestC) {
            if ($subject->value1 != $this->value1) {
                return $this->value1 - $subject->value1;
            }
            if ($subject->value2 != $this->value2) {
                return $this->value2 - $subject->value2;
            }
            return 0;
        }
        else {
            return 1;
        }
    }
}
?>