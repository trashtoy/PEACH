<?php
/**
 * Test class for Peach_Util_ArrayMap.
 */
class Peach_Util_ArrayMapTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Util_ArrayMap
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Util_ArrayMap();
        $this->object->put("key1", "foo");
        $this->object->put("key2", "bar");
        $this->object->put("key3", "baz");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * 配列, Map 以外の型を引数に指定した場合に InvalidArgumentException
     * をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     */
    public function testConstructorFail()
    {
        new Peach_Util_ArrayMap("hoge");
    }
    
    /**
     * get() をテストします. 以下を確認します.
     * 
     * - 引数のキーのマッピングが存在する場合, マッピングされている値を返す
     * - 引数のキーのマッピングが存在しない場合 null を返す
     * - 引数のキーのマッピングが存在せず, 第 2 引数でデフォルト値が指定されている場合はデフォルト値を返す
     * 
     * @covers Peach_Util_ArrayMap::get
     */
    public function testGet()
    {
        $test1 = $this->object->get("key1");
        $this->assertSame("foo", $test1);
        $test2 = $this->object->get("key4");
        $this->assertNull($test2);
        $test3 = $this->object->get("key5", "DEF");
        $this->assertSame("DEF", $test3);
    }
    
    /**
     * 引数に指定した値でマッピングが上書きされることを確認します.
     * 
     * @covers Peach_Util_ArrayMap::put
     */
    public function testPut()
    {
        $map = $this->object;
        $map->put("test", "X");
        $this->assertEquals("X", $map->get("test"));
        $map->put("test", "Y");
        $this->assertEquals("Y", $map->get("test"));
    }
    
    /**
     * 引数の Map のマッピングが適用されることを確認します.
     * 
     * @covers Peach_Util_ArrayMap::putAll
     */
    public function testPutAll()
    {
        $map = new Peach_Util_ArrayMap();
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
    
    /**
     * containesKey() のテストです.
     * 以下を確認します.
     * 
     * - 存在しないキー名を指定した場合は false を返す
     * - 存在するキー名を指定した場合は true を返す
     * - 値に null をセットしたエントリについても true を返す (Issue #1)
     */
    public function testContainsKey()
    {
        $this->object->put("test1", null);
        $this->assertFalse($this->object->containsKey("key4"));
        $this->assertTrue($this->object->containsKey("key2"));
        $this->assertTrue($this->object->containsKey("test1"));
    }
    
    /**
     * remove() をテストします. 以下を確認します.
     * 
     * - 引数に指定されたキーのマッピングが削除されること
     * - 存在しないキーを指定された場合は何も変化せず正常終了すること
     * 
     * @covers Peach_Util_ArrayMap::remove
     */
    public function testRemove()
    {
        $this->object->remove("key4");
        $this->assertSame(3, $this->object->size());
        $this->object->remove("key3");
        $this->assertSame(2, $this->object->size());
        $this->assertFalse($this->object->containsKey("key3"));
    }
    
    /**
     * マッピングが空になることを確認します.
     * 
     * @covers Peach_Util_ArrayMap::clear
     */
    public function testClear()
    {
        $this->object->clear();
        $data = $this->object->asArray();
        $this->assertSame(array(), $data);
    }
    
    /**
     * マッピングの個数を整数で返すことを確認します.
     * 
     * @covers Peach_Util_ArrayMap::size
     */
    public function testSize()
    {
        $this->assertSame(3, $this->object->size());
    }
    
    /**
     * キーの一覧を配列で返すことを確認します.
     * 
     * @covers Peach_Util_ArrayMap::keys
     */
    public function testKeys()
    {
        $expected = array("key1", "key2", "key3");
        $keys     = $this->object->keys();
        $this->assertSame($expected, $keys);
    }
    
    /**
     * 値の一覧を配列で返すことを確認します.
     * 
     * @covers Peach_Util_ArrayMap::values
     */
    public function testValues()
    {
        $expected = array("foo", "bar", "baz");
        $values   = $this->object->values();
        $this->assertSame($expected, $values);
    }
    
    /**
     * entryList() をテストします. 以下を確認します.
     * 
     * - MapEntry の配列を返すこと
     * - MapEntry に対する変更がこの ArrayMap にも適用されること
     * 
     * @covers Peach_Util_ArrayMap::entryList
     */
    public function testEntryList()
    {
        $entryList = $this->object->entryList();
        $this->assertSame(3, count($entryList));
        $entryList[0]->setValue("asdf");
        $expected = array("key1" => "asdf", "key2" => "bar", "key3" => "baz");
        $this->assertSame($expected, $this->object->asArray());
    }
    
    /**
     * 返される配列が ArrayMap の各マッピングと同じキー・値を持つことを確認します.
     *  
     * @covers Peach_Util_ArrayMap::asArray
     */
    public function testAsArray()
    {
        $expected = array(
            "key1" => "foo",
            "key2" => "bar",
            "key3" => "baz"
        );
        $this->assertSame($expected, $this->object->asArray());
    }
}
