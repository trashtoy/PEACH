<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_Time
 */
abstract class DT_AbstractTimeTest extends PHPUnit_Framework_TestCase {
    public abstract function testGet();

    public abstract function testSet();

    public abstract function testSetAll();

    public abstract function testAdd();

    public abstract function testCompareTo();

    public abstract function testFormat();

    public abstract function testEquals();

    public abstract function testBefore();

    public abstract function testAfter();

    public abstract function testFormatTime();
}
?>