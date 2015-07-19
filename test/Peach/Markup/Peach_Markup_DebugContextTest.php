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
     * 引数に true を指定して初期化した場合に自動で echo が行われることを確認します.
     * 
     * @covers Peach_Markup_DebugContext::__construct
     */
    public function test__constructByEchoModeOn()
    {
        ob_start();
        $obj = new Peach_Markup_DebugContext(true);
        $obj->handleText(new Peach_Markup_Text("foobar"));
        $actual = ob_get_contents();
        ob_end_clean();
        $this->assertSame("Text\r\n", $actual);
    }
    
    /**
     * 引数に false を指定して初期化した場合は echo が行われないことを確認します.
     * 
     * @covers Peach_Markup_DebugContext::__construct
     */
    public function test__constructByEchoModeOff()
    {
        $obj = new Peach_Markup_DebugContext(false);
        $obj->handleText(new Peach_Markup_Text("foobar"));
        $this->assertFalse($this->hasOutput());
    }
    
    /**
     * getResult() のテストです.
     * 各ノードのクラス名とその入れ子構造が出力されることを確認します.
     * 
     * @covers Peach_Markup_DebugContext::getResult
     */
    public function testGetResult()
    {
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $node     = Peach_Markup_TestUtil::getTestNode();
        $context  = $this->object;
        $this->expectOutputString($expected);
        $context->handle($node);
        $this->assertSame($expected, $context->getResult());
    }
    
    /**
     * handleComment() のテストです.
     * @covers Peach_Markup_DebugContext::handleComment
     * @covers Peach_Markup_DebugContext::startNode
     * @covers Peach_Markup_DebugContext::handleContainer
     * @covers Peach_Markup_DebugContext::endNode
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
     * @covers Peach_Markup_DebugContext::startNode
     * @covers Peach_Markup_DebugContext::handleContainer
     * @covers Peach_Markup_DebugContext::endNode
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
     * @covers Peach_Markup_DebugContext::append
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
     * @covers Peach_Markup_DebugContext::startNode
     * @covers Peach_Markup_DebugContext::handleContainer
     * @covers Peach_Markup_DebugContext::endNode
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
     * @covers Peach_Markup_DebugContext::append
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
     * @covers Peach_Markup_DebugContext::append
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
     * @covers Peach_Markup_DebugContext::append
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
