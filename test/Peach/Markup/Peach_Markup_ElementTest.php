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
    public function testGetAndSetAttribute()
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
     * @covers Peach_Markup_Element::setAttributes
     */
    public function testGetAndSetAttributes()
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
