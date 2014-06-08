<?php
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_HelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Helper
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_Helper(new Peach_Markup_DefaultBuilder(), array("meta", "input", "br"));
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * createObject() のテストです. 以下について確認します.
     * 
     * - HelperObject 型のオブジェクトを返すこと
     * - 返り値の HelperObject が, createNode() によって生成された Component をラップしていること
     * - 第 2 引数を指定した場合, 生成される HelperObject の要素にその内容が属性としてセットされること
     * - 第 2 引数を指定したが, 生成される HelperObject が要素ではない場合は無視されること
     * 
     * @covers Peach_Markup_Helper::createObject
     * @todo   Implement testCreateObject().
     */
    public function testCreateObject()
    {
        $h    = $this->object;
        $obj  = $h->createObject("p");
        $this->assertInstanceOf("Peach_Markup_HelperObject", $obj);
        
        $h1   = $h->createObject(new Peach_Markup_ContainerElement("h1"));
        $div  = $h->createObject("div");
        $emp  = $h->createObject("");
        $this->assertEquals(new Peach_Markup_ContainerElement("h1"),  $h1->getNode());
        $this->assertEquals(new Peach_Markup_ContainerElement("div"), $div->getNode());
        $this->assertEquals(new Peach_Markup_NodeList(),              $emp->getNode());
        
        $obj2  = $h->createObject("input", array("type" => "text", "name" => "title", "value" => "sample attribute"));
        $input = new Peach_Markup_EmptyElement("input");
        $input->setAttributes(array("type" => "text", "name" => "title", "value" => "sample attribute"));
        $this->assertEquals($input, $obj2->getNode());
        
        $obj3  = $h->createObject(null, array("class" => "ignore_test"));
        $this->assertEquals(new Peach_Markup_NodeList(), $obj3->getNode());
    }
    
    /**
     * createNode() のテストです. 引数によって, 以下の結果が返ることを確認します.
     * 
     * - {@link Peach_Markup_Node Node} 型オブジェクトの場合: 引数自身
     * - {@link Peach_Markup_NodeList NodeList} 型オブジェクトの場合: 引数自身
     * - {@link Peach_Markup_HelperObject HelperObject} 型オブジェクトの場合: 引数のオブジェクトがラップしているノード
     * - 文字列の場合: 引数の文字列を要素名に持つ新しい {@link Peach_Markup_Element Element}
     * - null または空文字列の場合: 空の {@link Peach_Markup_NodeList NodeList}
     * - それ以外: 引数の文字列表現のテキストノード
     * 
     * @covers Peach_Markup_Helper::createNode
     */
    public function testCreateNode()
    {
        $h = $this->object;
        $node = new Peach_Markup_EmptyElement("br");
        $this->assertSame($node, $h->createNode($node));
        
        $nodeList = new Peach_Markup_NodeList(array("First", "Second", "Third"));
        $this->assertSame($nodeList, $h->createNode($nodeList));
        
        $div= new Peach_Markup_ContainerElement("div");
        $div->setAttribute("id", "test");
        $div->append("Sample Text");
        $ho = $h->createObject($div);
        $this->assertSame($div, $h->createNode($ho));
        
        $p = new Peach_Markup_ContainerElement("p");
        $this->assertEquals($p, $h->createNode("p"));
        
        $emptyList = new Peach_Markup_NodeList();
        $this->assertEquals($emptyList, $h->createNode(null));
        $this->assertEquals($emptyList, $h->createNode(""));
        
        $datetime = new Peach_DT_Datetime(2012, 5, 21, 7, 34);
        $textNode = new Peach_Markup_Text("2012-05-21 07:34");
        $this->assertEquals($textNode, $h->createNode($datetime));
    }
    
    /**
     * Helper にセットされた Builder の build() の結果が返ることを確認します.
     * @covers Peach_Markup_Helper::write
     */
    public function testWrite()
    {
        $h = $this->object;
        $o = $h->createObject(Peach_Markup_TestUtil::getTestNode());
        $this->assertSame(Peach_Markup_TestUtil::getDefaultBuildResult(), $h->write($o));
    }
}
