<?php
require_once(__DIR__ . "/Peach_Markup_ContextTest.php");
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_DebugContextTest extends Peach_Markup_ContextTest
{
    /**
     * @var Peach_Markup_DebugContext
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object   = new Peach_Markup_DebugContext();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * getResult() のテストです.
     * 各ノードのクラス名とその入れ子構造が出力されることを確認します.
     * 
     * @covers Peach_Markup_DebugContext::getResult
     */
    public function testGetResult()
    {
        $expected = implode("\r\n", array(
            "ContainerElement(html) {",
            "    ContainerElement(head) {",
            "        EmptyElement(meta)",
            "        ContainerElement(title) {",
            "            Text",
            "        }",
            "    }",
            "    ContainerElement(body) {",
            "        ContainerElement(form) {",
            "            Text",
            "            EmptyElement(input)",
            "            EmptyElement(br)",
            "            EmptyElement(input)",
            "            Text",
            "            EmptyElement(br)",
            "            EmptyElement(input)",
            "        }",
            "    }",
            "}",
        )) . "\r\n";
        $node    = Peach_Markup_TestUtil::getTestNode();
        $context = $this->object;
        $this->expectOutputString($expected);
        $context->handle($node);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleComment() のテストです.
     * @covers Peach_Markup_DebugContext::handleComment
     */
    public function testHandleComment()
    {
        $expected = implode("\r\n", array(
            "Comment {",
            "    Text",
            "}",
        )) . "\r\n";
        $context   = $this->object;
        $comment   = new Peach_Markup_Comment();
        $comment->append("This is test");
        $this->expectOutputString($expected);
        $context->handleComment($comment);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleContainerElement() のテストです.
     * @covers Peach_Markup_DebugContext::handleContainerElement
     */
    public function testHandleContainerElement()
    {
        $expected = implode("\r\n", array(
            "ContainerElement(div) {",
            "    Text",
            "}",
        )) . "\r\n";
        $context   = $this->object;
        $container = new Peach_Markup_ContainerElement("div");
        $container->append("This is test");
        $this->expectOutputString($expected);
        $context->handleContainerElement($container);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleEmptyElement() のテストです.
     * @covers Peach_Markup_DebugContext::handleEmptyElement
     */
    public function testHandleEmptyElement()
    {
        $expected = "EmptyElement(br)\r\n";
        $context  = $this->object;
        $emp      = new Peach_Markup_EmptyElement("br");
        $this->expectOutputString($expected);
        $context->handleEmptyElement($emp);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleNodeList() のテストです.
     * @covers Peach_Markup_DebugContext::handleNodeList
     */
    public function testHandleNodeList()
    {
        $expected = implode("\r\n", array(
            "NodeList {",
            "    Comment {",
            "        Text",
            "    }",
            "    EmptyElement(img)",
            "    Text",
            "}",
        )) . "\r\n";
        $nodeList = new Peach_Markup_NodeList();
        $comment  = new Peach_Markup_Comment();
        $comment->append("This is test comment");
        $img      = new Peach_Markup_EmptyElement("img");
        $img->setAttributes(array("src" => "test.jpg", "alt" => ""));
        $nodeList->append($comment);
        $nodeList->append($img);
        $nodeList->append("Test image");
        
        $context = $this->object;
        $this->expectOutputString($expected);
        $context->handleNodeList($nodeList);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleText() のテストです.
     * @covers Peach_Markup_DebugContext::handleText
     */
    public function testHandleText()
    {
        $expected = "Text\r\n";
        $context  = $this->object;
        $text     = new Peach_Markup_Text("This is test");
        $this->expectOutputString($expected);
        $context->handleText($text);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleCode() のテストです.
     * @covers Peach_Markup_DebugContext::handleCode
     */
    public function testHandleCode()
    {
        $expected = "Code\r\n";
        $context  = $this->object;
        $str      = implode("\n", array(
            "$(function() {",
            "    $(\"#navi\").hide();",
            "}",
        ));
        $code     = new Peach_Markup_Code($str);
        $this->expectOutputString($expected);
        $context->handleCode($code);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleNone() のテストです.
     * @covers Peach_Markup_DebugContext::handleNone
     */
    public function testHandleNone()
    {
        $expected = "None\r\n";
        $context  = $this->object;
        $none     = Peach_Markup_None::getInstance();
        $this->expectOutputString($expected);
        $context->handleNone($none);
        $this->assertSame($expected, $context->getResult());
    }
}
