<?php
require_once dirname(__FILE__) . '/../../src/Util/load.php';

/**
 * Test class for Util_ReverseComparator.
 */
class Util_ReverseComparatorTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Util_ReverseComparator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * DefaultComparator が 1, -1, 0 を返すケースで, 
     * ReverseComparator がそれぞれ -1, 1, 0 を返すことを確認します.
     */
    public function testCompare() {
        $c   = Util_DefaultComparator::getInstance();
        $r   = new Util_ReverseComparator($c);
        $this->assertSame($r->compare(10, 5), -1);
        $this->assertSame($r->compare(3,  8), 1);
        $this->assertSame($r->compare(4,  4), 0);
    }
}
?>