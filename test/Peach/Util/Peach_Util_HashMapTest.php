<?php
class Peach_Util_HashMapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Util_HashMap
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $map = new Peach_Util_HashMap();
        $map->put(new Peach_Util_HashMapTest_Object(10),   'foo');
        $map->put(new Peach_Util_HashMapTest_Object(20),   'bar');
        $map->put(new Peach_Util_HashMapTest_Object(null), 'hoge');
        $this->object = $map;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * {@link Peach_Util_HashMap::put()} をテストします.
     * 以下を確認します.
     * 
     * - put したマッピングが get で取得できることを確認する.
     * - 同一ではない, 等価なオブジェクトは同じキーとして扱われることを確認する.
     * - 存在しないマッピングの場合は NULL を返すことを確認する.
     * 
     * @covers Peach_Util_HashMap::put
     */
    public function testPut()
    {
        $map = $this->object;
        $o1  = new Peach_Util_HashMapTest_Object(10);
        $o2  = new Peach_Util_HashMapTest_Object(10);
        $o3  = new Peach_Util_HashMapTest_Object("foo");
        $o4  = new Peach_Util_HashMapTest_Object("bar");
        $map->put($o1, "asdf");
        $map->put($o3, "hoge");
        $this->assertSame("asdf", $map->get($o1));
        $this->assertSame("asdf", $map->get($o2));
        $this->assertSame("hoge", $map->get($o3));
        $this->assertSame(null,   $map->get($o4));
    }

    /**
     * @covers Peach_Util_HashMap::putAll
     */
    public function testPutAll()
    {
        $map = $this->object;
        $arr = array(1 => "foo", 3 => "bar", 5 => "baz");
        $map->putAll(new Peach_Util_ArrayMap($arr));
        $this->assertSame(6, $map->size());
        $this->assertSame("bar", $map->get(3));
    }
    
    /**
     * @covers Peach_Util_HashMap::get
     */
    public function testGet()
    {
        $this->assertSame("foo", $this->object->get(new Peach_Util_HashMapTest_Object("10")));
        $this->assertSame(null,  $this->object->get(new Peach_Util_HashMapTest_Object(1000)));
    }

    /**
     * @covers Peach_Util_HashMap::clear
     */
    public function testClear()
    {
        $this->object->clear();
        $this->assertSame(0, $this->object->size());
    }

    /**
     * @covers Peach_Util_HashMap::size
     */
    public function testSize()
    {
        $this->assertSame(3, $this->object->size());
    }

    /**
     * @covers Peach_Util_HashMap::keys
     */
    public function testKeys()
    {
        $map   = $this->getTestMap();
        $keys1 = $map->keys();
        $this->assertSame(100, count($keys1));
        $keys2 = Peach_Util_Arrays::sort($keys1);
        for ($i = 0; $i < 100; $i++) {
            $this->assertSame($i, $keys2[$i]->getValue());
        }
    }

    /**
     * @covers Peach_Util_HashMap::containsKey
     */
    public function testContainsKey()
    {
        $test1 = new Peach_Util_HashMapTest_Object(5);
        $this->assertSame(false, $this->object->containsKey($test1));
        $test2 = new Peach_Util_HashMapTest_Object(10);
        $this->assertSame(true,  $this->object->containsKey($test2));
    }

    /**
     * @covers Peach_Util_HashMap::remove
     */
    public function testRemove()
    {
        $map = $this->object;
        $map->remove(new Peach_Util_HashMapTest_Object(150));
        $this->assertSame(3, $map->size());
        
        $this->assertTrue($map->containsKey(new Peach_Util_HashMapTest_Object(20)));
        $map->remove(new Peach_Util_HashMapTest_Object(20));
        $this->assertSame(2, $map->size());
        $this->assertFalse($map->containsKey(new Peach_Util_HashMapTest_Object(20)));
    }

    /**
     * @covers Peach_Util_HashMap::values
     */
    public function testValues()
    {
        $map     = $this->getTestMap();
        $values1 = $map->values();
        $this->assertSame(100, count($values1));
        natsort($values1);
        $values2 = array_values($values1);
        for ($i = 0; $i < 100; $i ++) {
            $this->assertSame("VALUE:{$i}", $values2[$i]);
        }
    }

    /**
     * @covers Peach_Util_HashMap::entryList
     */
    public function testEntryList()
    {
        $map       = $this->getTestMap();
        $entryList = $map->entryList();
        $this->assertSame(100, count($entryList));
        foreach ($entryList as $entry) {
            $obj = $entry->getKey();
            $val = $obj->getValue();
            $this->assertSame("VALUE:{$val}", $entry->getValue());
        }
    }
    
    private function getTestObjectList()
    {
        static $objList = null;
        if (!isset($objList)) {
            $objList = array();
            for ($i = 0; $i < 100; $i ++) {
                $objList[] = new Peach_Util_HashMapTest_C($i);
            }
        }
        return $objList;
    }
    
    private function getTestMap()
    {
        $map = new Peach_Util_HashMap();
        $objList = $this->getTestObjectList();
        foreach ($objList as $key => $obj) {
            $map->put($obj, "VALUE:{$key}");
        }
        return $map;
    }
}

class Peach_Util_HashMapTest_Object
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}

class Peach_Util_HashMapTest_C implements Peach_Util_Comparable
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
    public function compareTo($subject)
    {
        return $this->value - $subject->value;
    }
    
    public function __toString()
    {
        return "KEY:{$this->value}";
    }
}
?>