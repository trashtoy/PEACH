<?php
require_once(__DIR__ . "/Peach_Markup_TestContext.php");

class Peach_Markup_TextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Text
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_Text("THIS IS TEST");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタに指定した文字列を返すことを確認します.
     * 
     * @covers Peach_Markup_Text::getText
     */
    public function testGetText()
    {
        $this->assertSame("THIS IS TEST", $this->object->getText());
    }
    
    /**
     * Context の handleText() を呼び出すことを確認します.
     * 
     * @covers Peach_Markup_Text::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleText", $context->getResult());
    }
    
    /**
     * コンストラクタに指定した文字列を返すことを確認します.
     * 
     * @covers Peach_Markup_Text::__toString
     */
    public function test__toString()
    {
        $this->assertSame("THIS IS TEST", $this->object->__toString());
    }
}
