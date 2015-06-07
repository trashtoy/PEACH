<?php
require_once(__DIR__ . "/Peach_Markup_ContextTest.php");
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");
class Peach_Markup_DefaultContextTest extends Peach_Markup_ContextTest
{
    /**
     * @var Peach_Markup_DefaultContext
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = $this->getTestObject();
    }
    
    /**
     * @return Peach_Markup_DefaultContext
     */
    private function getTestObject()
    {
        return new Peach_Markup_DefaultContext(Peach_Markup_XmlRenderer::getInstance());
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * handleComment のテストです. 以下を確認します.
     * 
     * - ノードが 1 つの場合 "<!--comment text-->" 形式になること
     * - ノードが複数の場合, コメントが改行されること
     * - 接頭辞および接尾辞が指定された場合は改行されること
     * - 内部に別のコメントノードがあった場合, そのノードの中にあるオブジェクトだけを処理すること
     * - 要素の中に 1 つだけコメントノードが含まれている場合 (indentMode が false の場合) に改行されないこと
     * 
     * @covers Peach_Markup_DefaultContext::handleComment
     * @covers Peach_Markup_DefaultContext::checkBreakModeInComment
     * @covers Peach_Markup_DefaultContext::formatChildNodes
     * @covers Peach_Markup_DefaultContext::breakCode
     * @covers Peach_Markup_DefaultContext::escapeEndComment
     */
    public function testHandleComment()
    {
        $expected1 = "<!---->";
        $comment   = new Peach_Markup_Comment();
        $obj1      = $this->object;
        $obj1->handleComment($comment);
        $this->assertSame($expected1, $obj1->getResult());
        
        $expected2 = "<!--SAMPLE TEXT-->";
        $comment->append("SAMPLE TEXT");
        $obj2      = $this->getTestObject();
        $obj2->handleComment($comment);
        $this->assertSame($expected2, $obj2->getResult());
        
        $expected3 = implode("\r\n", array(
            "<!--",
            "SAMPLE TEXT",
            "<element />",
            "-->",
        ));
        $comment->append(new Peach_Markup_EmptyElement("element"));
        $obj3      = $this->getTestObject();
        $obj3->handleComment($comment);
        $this->assertSame($expected3, $obj3->getResult());
        
        $expected4 = implode("\r\n", array(
            '<!--[if IE 9]>',
            '<script src="sample.js"></script>',
            '<![endif]-->'
        ));
        $script    = new Peach_Markup_ContainerElement("script");
        $script->setAttribute("src", "sample.js");
        $comment2  = new Peach_Markup_Comment("[if IE 9]>", "<![endif]");
        $comment2->append($script);
        $obj4      = $this->getTestObject();
        $obj4->handleComment($comment2);
        $this->assertSame($expected4, $obj4->getResult());
        
        $expected5 = "<!--TEST-->";
        $comment3  = new Peach_Markup_Comment();
        $comment3->append("TEST");
        $comment4  = new Peach_Markup_Comment();
        $comment4->append($comment3);
        $obj5      = $this->getTestObject();
        $obj5->handleComment($comment4);
        $this->assertSame($expected5, $obj5->getResult());
        
        $expected6 = "<div><!--TEST--></div>";
        $div       = new Peach_Markup_ContainerElement("div");
        $comment5  = new Peach_Markup_Comment();
        $comment5->append("TEST");
        $div->append($comment5);
        $obj6      = $this->getTestObject();
        $obj6->handle($div);
        $this->assertSame($expected6, $obj6->getResult());
    }
    
    /**
     * handleText のテストです. 以下を確認します.
     * 
     * - 改行コード "\r", "\n", "\r\n" が文字参照 "&#xa;" に置換されること
     * - 特殊文字がエスケープされること
     * 
     * @covers Peach_Markup_DefaultContext::handleText
     * @covers Peach_Markup_DefaultContext::indent
     * @covers Peach_Markup_DefaultContext::escape
     */
    public function testHandleText()
    {
        $text  = new Peach_Markup_Text("THIS IS SAMPLE");
        $obj1  = $this->object;
        $obj1->handleText($text);
        $this->assertSame("THIS IS SAMPLE", $obj1->getResult());
        
        $lines = new Peach_Markup_Text("FIRST\nSECOND\rTHIRD\r\nFOURTH\n\rFIFTH");
        $obj2  = $this->getTestObject();
        $obj2->handleText($lines);
        $this->assertSame("FIRST&#xa;SECOND&#xa;THIRD&#xa;FOURTH&#xa;&#xa;FIFTH", $obj2->getResult());
        
        $text2 = new Peach_Markup_Text("TEST & <!-- <script>alert(0);</script> -->");
        $obj3  = $this->getTestObject();
        $obj3->handleText($text2);
        $this->assertSame("TEST &amp; &lt;!-- &lt;script&gt;alert(0);&lt;/script&gt; --&gt;", $obj3->getResult());
    }

    /**
     * 指定されたコードがインデントされた状態でマークアップされることを確認します.
     * 
     * @covers Peach_Markup_DefaultContext::handleCode
     * @covers Peach_Markup_DefaultContext::indent
     * @covers Peach_Markup_DefaultContext::breakCode
     */
    public function testHandleCode()
    {
        $text = <<<EOS
body {
    color            : #000;
    background-color : #fff;
    width            : 750px;
}
@media(min-width : 1024px) {
    body {
        width : 1000px;
    }
}
EOS;
        $code  = new Peach_Markup_Code($text);
        $style = new Peach_Markup_ContainerElement("style");
        $style->setAttribute("type", "text/css");
        $style->append($code);
        $head  = new Peach_Markup_ContainerElement("head");
        $head->append($style);
        
        $expected = implode("\r\n", array(
            '<head>',
            '    <style type="text/css">',
            '        body {',
            '            color            : #000;',
            '            background-color : #fff;',
            '            width            : 750px;',
            '        }',
            '        @media(min-width : 1024px) {',
            '            body {',
            '                width : 1000px;',
            '            }',
            '        }',
            '    </style>',
            '</head>',
        ));
        $this->object->handle($head);
        $this->assertSame($expected, $this->object->getResult());
    }

    /**
     * 空文字列の Code オブジェクトについては何も出力しないことを確認します.
     * 
     * @covers Peach_Markup_DefaultContext::handleCode
     */
    public function testHandleCodeByEmptyString()
    {
        $container = new Peach_Markup_NodeList();
        $container->append(new Peach_Markup_Text("First"));
        $container->append(new Peach_Markup_Code(""));
        $container->append(new Peach_Markup_Text("Second"));
        
        $obj = $this->object;
        $obj->handle($container);
        
        $expected = "First\r\n\r\nSecond";
        $this->assertSame($expected, $obj->getResult());
    }
    
    /**
     * handleEmptyElement のテストです. 以下を確認します.
     * 
     * - SGML 形式の場合 "<tagName>" となること
     * - XML 形式の場合 "<tagName />" となること
     * 
     * @covers Peach_Markup_DefaultContext::handleEmptyElement
     * @covers Peach_Markup_DefaultContext::indent
     */
    public function testHandleEmptyElement()
    {
        $input     = new Peach_Markup_EmptyElement("input");
        $input->setAttributes(array("type" => "checkbox", "name" => "flag", "value" => 1));
        $input->setAttribute("checked");
        
        $expected1 = '<input type="checkbox" name="flag" value="1" checked="checked" />';
        $obj1      = new Peach_Markup_DefaultContext(Peach_Markup_XmlRenderer::getInstance());
        $obj1->handleEmptyElement($input);
        $this->assertSame($expected1, $obj1->getResult());
        
        $expected2 = '<input type="checkbox" name="flag" value="1" checked>';
        $obj2      = new Peach_Markup_DefaultContext(Peach_Markup_SgmlRenderer::getInstance());
        $obj2->handleEmptyElement($input);
        $this->assertSame($expected2, $obj2->getResult());
    }

    /**
     * ContainerElement のテストです. 以下を確認します.
     * 
     * - 要素数が 0 の時は "<tagName></tagName>" 形式
     * - 要素数が 1 の時は "<tagName>NODE</tagName>" 形式
     * - 複数のノードを持つ子孫ノードが存在する場合は, 改行してインデントすること
     * 
     * @covers Peach_Markup_DefaultContext::handleContainerElement
     * @covers Peach_Markup_DefaultContext::formatChildNodes
     * @covers Peach_Markup_DefaultContext::breakCode
     */
    public function testHandleContainerElement()
    {
        $expected1 = "<sample></sample>";
        $node1     = new Peach_Markup_ContainerElement("sample");
        $obj1      = $this->object;
        $obj1->handleContainerElement($node1);
        $this->assertSame($expected1, $obj1->getResult());
        
        $expected2 = "<sample><empty /></sample>";
        $node2     = new Peach_Markup_ContainerElement("sample");
        $node2->append(new Peach_Markup_EmptyElement("empty"));
        $obj2      = $this->getTestObject();
        $obj2->handleContainerElement($node2);
        $this->assertSame($expected2, $obj2->getResult());
        
        $expected3 = "<sample><test></test></sample>";
        $node3     = new Peach_Markup_ContainerElement("sample");
        $child     = new Peach_Markup_ContainerElement("test");
        $node3->append($child);
        $obj3      = $this->getTestObject();
        $obj3->handleContainerElement($node3);
        $this->assertSame($expected3, $obj3->getResult());
        
        $expected4 = "<sample><test>NODE1</test></sample>";
        $child->append("NODE1");
        $obj4      = $this->getTestObject();
        $obj4->handleContainerElement($node3);
        $this->assertSame($expected4, $obj4->getResult());
        
        $expected5 = implode("\r\n", array(
            "<sample>",
            "    <test>",
            "        NODE1",
            "        NODE2",
            "    </test>",
            "</sample>",
        ));
        $child->append("NODE2");
        $obj5      = $this->getTestObject();
        $obj5->handleContainerElement($node3);
        $this->assertSame($expected5, $obj5->getResult());
    }

    /**
     * NodeList に含まれる各子ノードを handle することを確認します.
     * 
     * @covers Peach_Markup_DefaultContext::handleNodeList
     * @covers Peach_Markup_DefaultContext::formatChildNodes
     * @covers Peach_Markup_DefaultContext::breakCode
     */
    public function testHandleNodeList()
    {
        $node1    = new Peach_Markup_EmptyElement("empty");
        $node2    = new Peach_Markup_Text("Sample Text");
        $node3    = new Peach_Markup_ContainerElement("container");
        $node3->append("TEST");
        $nodeList = new Peach_Markup_NodeList();
        $nodeList->append($node1);
        $nodeList->append($node2);
        $nodeList->append($node3);
        $expected = implode("\r\n", array(
            '<empty />',
            'Sample Text',
            '<container>TEST</container>',
        ));
        $this->object->handleNodeList($nodeList);
        $this->assertSame($expected, $this->object->getResult());
    }
    
    /**
     * 結果に何も追加されないことを確認します。
     * 
     * @covers Peach_Markup_DefaultContext::handleNone
     */
    public function testHandleNone()
    {
        $none = Peach_Markup_None::getInstance();
        $obj  = $this->object;
        $obj->handle($none);
        $this->assertSame("", $obj->getResult());
    }
    
    /**
     * @covers Peach_Markup_DefaultContext::__construct
     * @covers Peach_Markup_DefaultContext::getResult
     */
    public function testGetResult()
    {
        $test    = Peach_Markup_TestUtil::getTestNode();
        $expect1 = Peach_Markup_TestUtil::getDefaultBuildResult();
        $obj1 = $this->object;
        $obj1->handle($test);
        $this->assertSame($expect1, $obj1->getResult());
        
        $expect2 = Peach_Markup_TestUtil::getCustomBuildResult();
        $indent = new Peach_Markup_Indent(0, "  ", Peach_Markup_Indent::LF);
        $obj2   = new Peach_Markup_DefaultContext(Peach_Markup_SgmlRenderer::getInstance(), $indent);
        $obj2->handle($test);
        $this->assertSame($expect2, $obj2->getResult());
    }
}
