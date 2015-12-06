<?php
abstract class Peach_Markup_ElementTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Peach_Markup_Element
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタに空文字列を指定した場合に
     * InvalidArgumentException をスローすることを確認します.
     */
    abstract public function test__constructFailByEmptyName();
    
    /**
     * コンストラクタに要素名として不正な値を指定した場合に
     * InvalidArgumentException をスローすることを確認します.
     */
    abstract public function test__constructFailByInvalidName();
    
    /**
     * @covers Peach_Markup_Element::getName
     */
    abstract public function testGetName();
    
    /**
     * getAttribute(), setAttribute() のテストです.
     * setAttribute() の以下の振る舞いを確認します.
     * 
     * - 第二引数を省略した場合, 値が null の属性が追加される
     * 
     * getAttribute() の以下の振る舞いを確認します.
     * 
     * - 存在しない属性が指定された場合は null を返す
     * - 値の省略された属性の場合は null を返す
     * - 存在する属性が指定された場合は, その属性値の文字列表現を返す
     * 
     * @covers Peach_Markup_Element::getAttribute
     * @covers Peach_Markup_Element::setAttribute
     */
    public function testAccessAttribute()
    {
        $obj = $this->object;
        $obj->setAttribute("option");
        $obj->setAttribute("str", "some string");
        $obj->setAttribute("num", 123);
        $this->assertNull($obj->getAttribute("notfound"));
        $this->assertNull($obj->getAttribute("option"));
        $this->assertSame("some string", $obj->getAttribute("str"));
        $this->assertSame("123",         $obj->getAttribute("num"));
    }
    
    /**
     * 存在する属性名を指定した場合のみ true を返すことを確認します.
     * 
     * @covers Peach_Markup_Element::hasAttribute
     */
    public function testHasAttribute()
    {
        $obj = $this->object;
        $obj->setAttribute("option");
        $obj->setAttribute("str", "some string");
        $this->assertTrue($obj->hasAttribute("option"));
        $this->assertTrue($obj->hasAttribute("str"));
        $this->assertFalse($obj->hasAttribute("notfound"));
    }
    
    /**
     * setAttributes() および getAttributes() のテストです. 以下を確認します.
     * - 引数に指定した配列が属性としてセットされること
     * - 引数に指定した Map オブジェクトが属性としてセットされること
     * - 配列のキーに整数が指定されている場合, その値が Boolean 属性としてセットされること
     * 
     * @covers Peach_Markup_Element::setAttributes
     * @covers Peach_Markup_Element::getAttributes
     */
    public function testAccessAttributes()
    {
        $arr = array("first" => 1, "second" => 2, "third" => 3);
        
        $map = new Peach_Util_ArrayMap();
        $map->put("aaa", "xxxx");
        $map->put("bbb", "yyyy");
        $map->put("ccc", "zzzz");
        
        $expected = array(
            "first"  => "1",
            "second" => "2",
            "third"  => "3",
            "aaa"    => "xxxx",
            "bbb"    => "yyyy",
            "ccc"    => "zzzz",
        );
        
        $obj = $this->object;
        $obj->setAttributes($arr);
        $obj->setAttributes($map);
        $this->assertSame($expected, $obj->getAttributes());
    }
    
    /**
     * setAttributes() の引数に配列, Map 以外の値を指定した場合に
     * InvalidArgumentException をスローすることを確認します.
     * 
     * @covers Peach_Markup_Element::setAttributes
     * @expectedException InvalidArgumentException
     */
    public function testSetAttributesFail()
    {
        $this->object->setAttributes("hoge");
    }
    
    /**
     * setAttributes() の配列のキーに整数を指定した場合,
     * キーは無視されて値が Boolean 属性として登録されることを確認します.
     * 
     * @covers Peach_Markup_Element::setAttributes
     * @covers Peach_Markup_Element::getAttributes
     */
    public function testAccessBooleanAttributes()
    {
        $arr      = array("type" => "checkbox", "checked", "name" => "flag", "value" => 1);
        $expected = array(
            "type"    => "checkbox",
            "checked" => null,
            "name"    => "flag",
            "value"   => "1",
        );
        $obj = $this->object;
        $obj->setAttributes($arr);
        $this->assertSame($expected, $obj->getAttributes());
    }
    
    /**
     * removeAttribute() をテストします.
     * 以下を確認します.
     * 
     * - 属性が存在するかどうかに限らず, 任意の属性名を指定して実行できる
     * - 指定された属性が, 属性の一覧から取り除かれる
     * 
     * @covers Peach_Markup_Element::removeAttribute
     */
    public function testRemoveAttribute()
    {
        $obj = $this->object;
        $obj->setAttributes(array("first" => 1, "second" => 2, "option" => null));
        $obj->removeAttribute("second");
        $obj->removeAttribute("forth");
        
        $expected = array("first" => "1", "option" => null);
        $this->assertSame($expected, $obj->getAttributes());
    }
}
