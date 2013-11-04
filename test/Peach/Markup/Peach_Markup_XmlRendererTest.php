<?php
require_once(__DIR__ . "/Peach_Markup_AbstractRendererTest.php");

class Peach_Markup_XmlRendererTest extends Peach_Markup_AbstractRendererTest
{
    /**
     * @var Peach_Markup_XmlRenderer
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Markup_XmlRenderer::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * getInstance() をテストします.
     * 以下を確認します.
     * 
     * - 返り値が Peach_Markup_XmlRenderer のインスタンスである
     * - どの返り値も, 同一のインスタンスを返す
     * 
     * @covers Peach_Markup_XmlRenderer::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_Markup_XmlRenderer::getInstance();
        $obj2 = Peach_Markup_XmlRenderer::getInstance();
        $this->assertSame("Peach_Markup_XmlRenderer", get_class($obj1));
        $this->assertSame($obj1, $obj2);
    }
    
    /**
     * 空要素タグの出力をテストします.
     * @covers Peach_Markup_AbstractRenderer::formatEmptyTag
     */
    public function testFormatEmptyTag()
    {
        $obj  = $this->object;
        
        $e1   = new Peach_Markup_EmptyElement("br");
        $this->assertSame("<br />", $obj->formatEmptyTag($e1));
        
        $e2   = new Peach_Markup_EmptyElement("img");
        $e2->setAttributes(array("src" => "test.png", "alt" => "TEST"));
        $this->assertSame('<img src="test.png" alt="TEST" />', $obj->formatEmptyTag($e2));
    }
    
    /**
     * 終了タグの出力をテストします.
     * @covers Peach_Markup_AbstractRenderer::formatEndTag
     */
    public function testFormatEndTag()
    {
        $e1   = new Peach_Markup_ContainerElement("testTag");
        $this->assertSame("</testTag>", $this->object->formatEndTag($e1));
    }
    
    /**
     * 開始タグの出力をテストします.
     * @covers Peach_Markup_AbstractRenderer::formatStartTag
     */
    public function testFormatStartTag()
    {
        $obj  = $this->object;
        
        $e1   = new Peach_Markup_ContainerElement("testTag");
        $this->assertSame("<testTag>", $obj->formatStartTag($e1));
        
        $e2   = new Peach_Markup_ContainerElement("test1");
        $e2->setAttributes(array("name" => "hoge", "value" => "123"));
        $e2->setAttribute("option");
        $this->assertSame('<test1 name="hoge" value="123" option="option">', $obj->formatStartTag($e2));
        
        $e3   = new Peach_Markup_ContainerElement("test2");
        $e3->setAttributes(array("name" => "fuga", "option1" => null, "option2" => null, "value" => 234));
        $this->assertSame('<test2 name="fuga" option1="option1" option2="option2" value="234">', $obj->formatStartTag($e3));
    }
}
