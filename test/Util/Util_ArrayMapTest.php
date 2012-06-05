<?php

require_once 'Util/ArrayMap.php';

class Util_ArrayMapTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Util_ArrayMap
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new Util_ArrayMap();
        $this->object->put("key1", "foo");
        $this->object->put("key2", "bar");
        $this->object->put("key3", "baz");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * 
     * @expectedException Exception
     */
    public function testConstructorFail() {
        $map = new Util_ArrayMap("hoge");
    }
    
    public function testGet() {
        $test = $this->object->get("key1");
        $this->assertSame("foo", $test);
        $test = $this->object->get("key4");
        $this->assertNull($test);
        $test = $this->object->get("key5", "DEF");
        $this->assertSame("DEF", $test);
    }
    
    public function testPut() {
        $map = $this->object;
        $map->put('test', 'X');
        $this->assertEquals('X', $map->get('test'));
        $map->put('test', 'Y');
        $this->assertEquals('Y', $map->get('test'));
    }
    
    public function testPutAll() {
        $map = new Util_ArrayMap();
        $map->put("test1", "hoge");
        $map->put("test2", "fuga");
        $map->put("key3",  "piyo");
        $this->object->putAll($map);
        $arr = $this->object->asArray();
        $expected = array(
            "key1"  => "foo",
            "key2"  => "bar",
            "key3"  => "piyo",
            "test1" => "hoge",
            "test2" => "fuga"
        );
        $this->assertSame($expected, $arr);
    }
    
    public function testContainsKey() {
        $this->assertFalse($this->object->containsKey("key4"));
        $this->assertTrue($this->object->containsKey("key2"));
    }
    
    public function testRemove() {
        $this->object->remove("key4");
        $this->assertSame(3, $this->object->size());
        $this->object->remove("key3");
        $this->assertSame(2, $this->object->size());
        $this->assertFalse($this->object->containsKey("key3"));
    }
    
    public function testClear() {
        $this->object->clear();
        $data = $this->object->asArray();
        $this->assertSame(array(), $data);
    }
    
    public function testSize() {
        $this->assertSame(3, $this->object->size());
    }
    
    public function testKeys() {
        $expected = array("key1", "key2", "key3");
        $keys     = $this->object->keys();
        $this->assertSame($expected, $keys);
    }
    
    public function testValues() {
        $expected = array("foo", "bar", "baz");
        $values   = $this->object->values();
        $this->assertSame($expected, $values);
    }
    
    public function testEntryList() {
        $entryList = $this->object->entryList();
        $this->assertSame(3, count($entryList));
        $entryList[0]->setValue("asdf");
        $expected = array("key1" => "asdf", "key2" => "bar", "key3" => "baz");
        $this->assertSame($expected, $this->object->asArray());
    }
    
    public function testAsArray() {
        $expected = array(
            "key1" => "foo",
            "key2" => "bar",
            "key3" => "baz"
        );
        $this->assertSame($expected, $this->object->asArray());
    }
}
?>