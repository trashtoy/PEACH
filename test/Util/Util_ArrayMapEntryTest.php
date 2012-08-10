<?php
require_once 'Util/load.php';

class Util_ArrayMapEntryTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Util_ArrayMap
     */
    private $map;
    private $entryList;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->map = new Util_ArrayMap();
        $this->map->put("key1", "foo");
        $this->map->put("key2", "bar");
        $this->map->put("key3", "baz");
        $this->entryList = $this->map->entryList();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    public function testGetKey() {
        $this->assertSame("key2", $this->entryList[1]->getKey());
    }
    
    public function testGetValue() {
        $this->assertSame("baz", $this->entryList[2]->getValue());
    }
    
    public function testSetValue() {
        $this->entryList[0]->setValue("hoge");
        $this->assertSame("hoge", $this->entryList[0]->getValue());
        $expected  = array("key1" => "hoge", "key2" => "bar", "key3" => "baz");
        $this->assertSame($expected, $this->map->asArray());
    }
}

?>