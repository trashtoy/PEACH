<?php
require_once(__DIR__ . "/Peach_Markup_TestContext.php");
require_once(__DIR__ . "/Peach_Markup_ContainerTestImpl.php");

class Peach_Markup_NodeListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_NodeList
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_NodeList();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * Container で定義されている append() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_NodeList::append
     * @see    Peach_Markup_ContainerTestImpl::testAppend
     */
    public function testAppend()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testAppend();
    }
    
    /**
     * Context の handleNodeList() が呼び出されることを確認します.
     * 
     * @covers Peach_Markup_NodeList::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleNodeList", $context->getResult());
    }
    
    /**
     * 追加されたノードの個数を返すことを確認します.
     * 
     * @covers Peach_Markup_NodeList::size
     */
    public function testSize()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testSize();
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
}
