<?php

require_once "Util/load.php";

class Util_HashMapTest extends PHPUnit_Framework_TestCase {
    /**
     * @var Util_HashMap
     */
    protected $object;
    
    public function setUp() {
        $map = new Util_HashMap();
        $map->put(new Util_HashMapTest_TestObject(10),   'foo');
        $map->put(new Util_HashMapTest_TestObject(20),   'bar');
        $map->put(new Util_HashMapTest_TestObject(NULL), 'hoge');
        $this->object = $map;
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * - put したマッピングが get で取得できることを確認する.
     * - 同一ではない, 等価なオブジェクトは同じキーとして扱われることを確認する.
     * - 存在しないマッピングの場合は NULL を返すことを確認する.
     */
    public function testPut() {
        $map = $this->object;
        $o1  = new Util_HashMapTest_TestObject(10);
        $o2  = new Util_HashMapTest_TestObject(10);
        $o3  = new Util_HashMapTest_TestObject("foo");
        $o4  = new Util_HashMapTest_TestObject("bar");
        $map->put($o1, "asdf");
        $map->put($o3, "hoge");
        $this->assertSame("asdf", $map->get($o1));
        $this->assertSame("asdf", $map->get($o2));
        $this->assertSame("hoge", $map->get($o3));
        $this->assertSame(NULL,   $map->get($o4));
    }
    
    public function testPutAll() {
        $map = $this->object;
        $arr = array(1 => "foo", 3 => "bar", 5 => "baz");
        $map->putAll(new Util_ArrayMap($arr));
        $this->assertSame(6, $map->size());
        $this->assertSame("bar", $map->get(3));
    }
    
    public function testGet() {
        $this->assertSame("foo", $this->object->get(new Util_HashMapTest_TestObject("10")));
        $this->assertSame(NULL,  $this->object->get(new Util_HashMapTest_TestObject(1000)));
    }
    
    public function testClear() {
        $this->object->clear();
        $this->assertSame(0, $this->object->size());
    }
    
    public function testSize() {
        $this->assertSame(3, $this->object->size());
    }
    
    public function testKeys() {
        $map  = $this->getTestMap();
        $keys = $map->keys();
        $this->assertSame(100, count($keys));
        $keys = Util_Arrays::sort($keys);
        for ($i = 0; $i < 100; $i ++) {
            $this->assertSame($i, $keys[$i]->getValue());
        }
    }
    
    public function testContainsKey() {
        $test = new Util_HashMapTest_TestObject(5);
        $this->assertSame(FALSE, $this->object->containsKey($test));
        $test = new Util_HashMapTest_TestObject(10);
        $this->assertSame(TRUE,  $this->object->containsKey($test));
    }
    
    public function testRemove() {
        $map = $this->object;
        $map->remove(new Util_HashMapTest_TestObject(150));
        $this->assertSame(3, $map->size());
        
        $this->assertTrue($map->containsKey(new Util_HashMapTest_TestObject(20)));
        $map->remove(new Util_HashMapTest_TestObject(20));
        $this->assertSame(2, $map->size());
        $this->assertFalse($map->containsKey(new Util_HashMapTest_TestObject(20)));
    }
    
    public function testValues() {
        $map    = $this->getTestMap();
        $values = $map->values();
        $this->assertSame(100, count($values));
        natsort($values);
        $values = array_values($values);
        for ($i = 0; $i < 100; $i ++) {
            $this->assertSame("VALUE:{$i}", $values[$i]);
        }
    }
    
    public function testEntryList() {
        $map       = $this->getTestMap();
        $entryList = $map->entryList();
        $this->assertSame(100, count($entryList));
        $keys      = array();
        foreach ($entryList as $entry) {
            $obj = $entry->getKey();
            $val = $obj->getValue();
            $this->assertSame("VALUE:{$val}", $entry->getValue());
        }
    }
    
    private function getTestObjectList() {
        static $objList;
        if (!isset($objList)) {
            $objList = array();
            for ($i = 0; $i < 100; $i ++) {
                $objList[] = new Util_HashMapTest_C($i);
            }
        }
        return $objList;
    }
    
    private function getTestMap() {
        $map = new Util_HashMap();
        $objList = $this->getTestObjectList();
        foreach ($objList as $key => $obj) {
            $map->put($obj, "VALUE:{$key}");
        }
        return $map;
    }
}

class Util_HashMapTest_TestObject {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
}

class Util_HashMapTest_C implements Util_Comparable {
    private $value;
    public function __construct($value) {
        $this->value = $value;
    }
    public function getValue() {
        return $this->value;
    }
    public function compareTo($subject) {
        return $this->value - $subject->value;
    }
    public function __toString() {
        return "KEY:{$this->value}";
    }
}
?>