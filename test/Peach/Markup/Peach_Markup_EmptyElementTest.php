<?php
require_once(__DIR__ . "/Peach_Markup_ElementTest.php");

class Peach_Markup_EmptyElementTest extends Peach_Markup_ElementTest
{
    /**
     * @var Peach_Markup_EmptyElement
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_EmptyElement("testTag");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * Context の handleEmptyElement() が呼び出されることを確認します.
     * @covers Peach_Markup_EmptyElement::accept
     */
    public function testAccept()
    {
        $obj   = $this->object;
        $debug = new Peach_Markup_DebugContext(false);
        $obj->accept($debug);
        $this->assertSame("EmptyElement(testTag)\r\n", $debug->getResult());
    }
    
    /**
     * コンストラクタに指定した文字列を返すことを確認します.
     * @covers Peach_Markup_EmptyElement::getName
     */
    public function testGetName()
    {
        $obj   = $this->object;
        $this->assertSame("testTag", $obj->getName());
    }
}
