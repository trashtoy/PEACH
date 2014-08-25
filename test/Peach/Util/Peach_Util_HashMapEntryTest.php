<?php
class Peach_Util_HashMapEntryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Util_HashMapEntry
     */
    protected $object;
    
    /**
     *
     * @var Peach_Util_HashMap
     */
    private $map;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->map = new Peach_Util_HashMap();
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
     * 引数の Equator で等価と判断される場合のみ true を返すことを確認します.
     * 
     * @covers Peach_Util_HashMapEntry::keyEquals
     */
    public function testKeyEquals()
    {
        $entry = $this->object;
        $e     = Peach_Util_DefaultEquator::getInstance();
        $this->assertFalse($entry->keyEquals("first", $e));
        $this->assertTrue($entry->keyEquals("First", $e));
    }
    
    public function testGetValue()
    {
        $entry = $this->object;
        $this->assertSame(1, $entry->getValue());
    }
    
    /**
     * HashMapEntry に対する操作が, HashMap に適用されていることを確認します.
     * @covers Peach_Util_HashMapEntry::setValue
     */
    public function testSetValue()
    {
        $entry = $this->object;
        $entry->setValue(100);
        $this->assertSame(100, $this->map->get("First"));
    }
}
