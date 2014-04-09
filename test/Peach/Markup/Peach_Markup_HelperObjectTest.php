<?php
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_HelperObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Helper
     */
    protected $helper;
    
    /**
     * @var Peach_Markup_HelperObject
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->helper = new Peach_Markup_Helper(new Peach_Markup_DefaultBuilder(), array("meta", "input", "br"));
        $this->object = new Peach_Markup_HelperObject($this->helper, "sample");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタの第 2 引数に指定された値によって, 返される値が変化することを確認します.
     * 
     * - {@link Peach_Markup_Node Node} 型オブジェクトの場合: 同一のオブジェクト
     * - {@link Peach_Markup_HelperObject HelperObject} 型オブジェクトの場合: 引数のオブジェクトがラップしているノード
     * - 文字列の場合: 引数の文字列を要素名に持つ新しい {@link Peach_Markup_Element Element}
     * - null または空文字列の場合: 空の {@link Peach_Markup_NodeList NodeList}
     * - それ以外: 引数の文字列表現のテキストノード
     * 
     * @covers Peach_Markup_HelperObject::getNode
     */
    public function testGetNode()
    {
        $h    = $this->helper;
        
        $node = new Peach_Markup_EmptyElement("br");
        $obj1 = new Peach_Markup_HelperObject($h, $node);
        $this->assertSame($node, $obj1->getNode());
        
        $div  = new Peach_Markup_ContainerElement("div");
        $div->setAttribute("id", "test");
        $div->append("Sample Text");
        $ho   = new Peach_Markup_HelperObject($h, $div);
        $obj2 = new Peach_Markup_HelperObject($h, $ho);
        $this->assertSame($div, $obj2->getNode());
        
        $obj3 = new Peach_Markup_HelperObject($h, "p");
        $this->assertEquals(new Peach_Markup_ContainerElement("p"), $obj3->getNode());
        
        $emptyList = new Peach_Markup_NodeList();
        $obj4      = new Peach_Markup_HelperObject($h, null);
        $this->assertEquals($emptyList, $obj4->getNode());
        $obj5      = new Peach_Markup_HelperObject($h, "");
        $this->assertEquals($emptyList, $obj5->getNode());
        
        $datetime = new Peach_DT_Datetime(2012, 5, 21, 7, 34);
        $textNode = new Peach_Markup_Text("2012-05-21 07:34");
        $obj6     = new Peach_Markup_HelperObject($h, $datetime);
        $this->assertEquals($textNode, $obj6->getNode());
    }
    
    /**
     * ラップしているノードが Container の場合は子ノードが追加され,
     * そうでない場合は何も変化しないことを確認します.
     * 
     * @covers Peach_Markup_HelperObject::append
     */
    public function testAppend()
    {
        $h    = $this->helper;
        
        $obj1 = new Peach_Markup_HelperObject($h, "p");
        $obj1->append("Sample Text");
        $p    = new Peach_Markup_ContainerElement("p");
        $p->append("Sample Text");
        $this->assertEquals($p,  $obj1->getNode());
        
        $obj2 = new Peach_Markup_HelperObject($h, "br");
        $obj2->append("Sample Text");
        $br   = new Peach_Markup_EmptyElement("br");
        $this->assertEquals($br, $obj2->getNode());
    }
    
    /**
     * appendCode() のテストです. 以下を確認します.
     * 
     * - 引数に {@link Peach_Markup_Code Code} オブジェクトを指定した場合, 引数をそのまま子ノードとして追加すること
     * - 引数にそれ以外の値を指定した場合, 引数を Code オブジェクトに変換して子ノードとして追加すること
     * 
     * @covers Peach_Markup_HelperObject::appendCode
     */
    public function testAppendCode()
    {
        $h      = $this->helper;
        $str    = implode("\n", array(
            "$(function() {",
            "    $('#menu').hide();",
            "}",
        ));
        $code   = new Peach_Markup_Code($str);
        $script = new Peach_Markup_ContainerElement("script");
        $script->append($code);
        
        $obj1   = new Peach_Markup_HelperObject($h, "script");
        $obj1->appendCode($code);
        $this->assertEquals($script, $obj1->getNode());
        
        $obj2   = new Peach_Markup_HelperObject($h, "script");
        $obj2->appendCode($str);
        $this->assertEquals($script, $obj2->getNode());
    }
    
    /**
     * attr() のテストです. 以下を確認します.
     * 
     * - このオブジェクトがラップしているノードが Element ではなかった場合, 何も変化しないこと
     * - 引数に文字列を 1 つ指定した場合, 指定された名前の boolean 属性が追加されること
     * - 引数に文字列を 2 つ指定した場合, 指定された属性名および属性値を持つ属性が追加されること
     * - 引数に配列を指定した場合, キーを属性名, 値を属性値とする属性が追加されること
     * 
     * @covers Peach_Markup_HelperObject::attr
     * @todo   Implement testAttr().
     */
    public function testAttr()
    {
        $h    = $this->helper;
        $obj1 = new Peach_Markup_HelperObject($h, "");
        $obj1->attr(array("class" => "test"));
        $this->assertEquals(new Peach_Markup_NodeList(), $obj1->getNode());
        
        $obj2 = new Peach_Markup_HelperObject($h, "input");
        $obj2->attr("readonly");
        $obj2->attr("class", "test");
        $obj2->attr(array("name" => "age", "value" => 18));
        $attrList = array(
            "readonly" => null,
            "class"    => "test",
            "name"     => "age",
            "value"    => "18",
        );
        $this->assertEquals($attrList, $obj2->getNode()->getAttributes());
    }
    
    /**
     * children() のテストです. 以下の結果が返ることを確認します.
     * 
     * - ラップしているオブジェクトが NodeList だった場合は, そのオブジェクト自身
     * - ラップしているオブジェクトが Container だった場合は, その子ノード一覧をあらわす HelperObject
     * - それ以外は空の NodeList を表現する HelperObject
     * 
     * @covers Peach_Markup_HelperObject::children
     */
    public function testChildren()
    {
        $h = $this->helper;
        
        $obj1      = new Peach_Markup_HelperObject($h, null);
        $obj1->append("First")->append("Second")->append("Third");
        $this->assertSame($obj1, $obj1->children());
        
        $p         = new Peach_Markup_ContainerElement("p");
        $p->append("First");
        $p->append("Second");
        $p->append("Third");
        $obj2      = new Peach_Markup_HelperObject($h, $p);
        $this->assertEquals($obj1, $obj2->children());
        
        $expected  = new Peach_Markup_HelperObject($h, null);
        $br        = new Peach_Markup_EmptyElement("br");
        $obj3      = new Peach_Markup_HelperObject($h, $br);
        $this->assertEquals($expected, $obj3->children());
    }
    
    /**
     * このオブジェクトに紐付いている Builder の build() が実行されることを確認します. 
     * @covers Peach_Markup_HelperObject::write
     */
    public function testWrite()
    {
        $h1   = $this->helper;
        $obj1 = Peach_Markup_TestUtil::createTestHelperObject($h1);
        $this->assertSame(Peach_Markup_TestUtil::getDefaultBuildResult(), $obj1->write());
        
        $b    = new Peach_Markup_DefaultBuilder();
        $b->setRenderer("SGML");
        $b->setIndent(new Peach_Markup_Indent(0, "  ", Peach_Markup_Indent::LF));
        $h2   = new Peach_Markup_Helper($b, array("meta", "input", "br"));
        $obj2 = Peach_Markup_TestUtil::createTestHelperObject($h2);
        $this->assertSame(Peach_Markup_TestUtil::getCustomBuildResult(), $obj2->write());
    }

    /**
     * このオブジェクトがラップしているノードが DebugBuilder によって build() されることを確認します.
     * @covers Peach_Markup_HelperObject::debug
     */
    public function testDebug()
    {
        $h1       = $this->helper;
        $obj1     = Peach_Markup_TestUtil::createTestHelperObject($h1);
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $this->expectOutputString($expected);
        $this->assertSame($expected, $obj1->debug());
    }
    
    /**
     * @covers Peach_Markup_HelperObject::prototype
     * @todo   Implement testPrototype().
     */
    public function testPrototype()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    /**
     * このオブジェクトがラップしているノードの accept() が実行されることを確認します.
     * @covers Peach_Markup_HelperObject::accept
     */
    public function testAccept()
    {
        $h     = $this->helper;
        $obj   = new Peach_Markup_HelperObject($h, "div");
        $debug = new Peach_Markup_DebugContext(false);
        $obj->accept($debug);
        $this->assertSame("ContainerElement(div) {\r\n}\r\n", $debug->getResult());
    }
    
    /**
     * getChildNodes() のテストです. 以下を確認します.
     * 
     * - このオブジェクトがラップしているノードが Container だった場合はそのノードの childNodes() の結果を返すこと
     * - それ以外は空配列を返すこと
     * 
     * @covers Peach_Markup_HelperObject::getChildNodes
     * @todo   Implement testGetChildNodes().
     */
    public function testGetChildNodes()
    {
        $h        = $this->helper;
        $expected = array(
            new Peach_Markup_Text("First"),
            new Peach_Markup_Text("Second"),
            new Peach_Markup_Text("Third"),
        );
        
        $p        = new Peach_Markup_ContainerElement("p");
        $p->append("First");
        $p->append("Second");
        $p->append("Third");
        $obj1     = new Peach_Markup_HelperObject($h, $p);
        $this->assertEquals($expected, $obj1->getChildNodes());
        
        $text     = new Peach_Markup_Text("This is test");
        $obj2     = new Peach_Markup_HelperObject($h, $text);
        $this->assertSame(array(), $obj2->getChildNodes());
    }
}
