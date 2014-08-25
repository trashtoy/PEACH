<?php
require_once(__DIR__ . "/Peach_Markup_ElementTest.php");
require_once(__DIR__ . "/Peach_Markup_ContainerTestImpl.php");
require_once(__DIR__ . "/Peach_Markup_TestContext.php");

class Peach_Markup_ContainerElementTest extends Peach_Markup_ElementTest
{
    /**
     * @var Peach_Markup_ContainerElement
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_ContainerElement("testTag");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * 要素名が空文字列だった場合に InvalidArgumentException をスローすることを確認します.
     * @expectedException InvalidArgumentException
     * @covers Peach_Markup_ContainerElement::__construct
     */
    public function test__constructFail()
    {
        new Peach_Markup_ContainerElement("");
    }
    
    /**
     * Container で定義されている append() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_ContainerElement::append
     * @see    Peach_Markup_ContainerTestImpl::testAppend
     */
    public function testAppend()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testAppend();
    }
    
    /**
     * Container で定義されている getChildNodes() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_ContainerElement::getChildNodes
     * @see    Peach_Markup_ContainerTestImpl::testChildNodes
     */
    public function testGetChildNodes()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testGetChildNodes();
    }
    
    /**
     * Context の handleContainerElement() が呼び出されることを確認します.
     * @covers Peach_Markup_ContainerElement::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleContainerElement", $context->getResult());
    }
    
    /**
     * 追加されたノードの個数を返すことを確認します.
     * 
     * @covers Peach_Markup_ContainerElement::size
     */
    public function testSize()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testSize();
    }
    
    /**
     * コンストラクタに指定した文字列を返すことを確認します.
     * @covers Peach_Markup_ContainerElement::getName
     */
    public function testGetName()
    {
        $obj   = $this->object;
        $this->assertSame("testTag", $obj->getName());
    }
}
