<?php
require_once dirname(__FILE__) . '/../../src/Util/load.php';

class Util_HashMapEntryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Util_HashMapEntry
     */
    protected $object;
    
    /**
     * @var Util_HashMap
     */
    private $map;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->map = new Util_HashMap();
        $this->map->put("First", 1);
        $entryList = $this->map->entryList();
        $this->object = $entryList[0];
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @todo Implement testKeyEquals().
     */
    public function testKeyEquals()
    {
        $entry = $this->object;
        $e     = Util_DefaultEquator::getInstance();
        $this->assertFalse($entry->keyEquals("first", $e));
        $this->assertTrue($entry->keyEquals("First", $e));
    }
    
    public function testGetValue()
    {
        $entry = $this->object;
        $this->assertSame(1, $entry->getValue());
    }
    
    public function testSetValue()
    {
        $entry = $this->object;
        $entry->setValue(100);
        $this->assertSame(100, $this->map->get("First"));
    }
}
?>