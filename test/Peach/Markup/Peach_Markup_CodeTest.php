<?php
require_once(__DIR__ . "/Peach_Markup_TestContext.php");

class Peach_Markup_CodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Code
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_Code("THIS IS SAMPLE");
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
     * @covers Peach_Markup_Code::__construct
     * @covers Peach_Markup_Code::getText
     */
    public function testGetText()
    {
        $code = new Peach_Markup_Code("THIS IS SAMPLE");
        $this->assertSame("THIS IS SAMPLE", $code->getText());
    }
    
    /**
     * Context の handleCode() が呼び出されることを確認します.
     * 
     * @covers Peach_Markup_Code::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleCode", $context->getResult());
    }
    
    /**
     * コンストラクタに指定した文字列を返すことを確認します.
     * 
     * @covers Peach_Markup_Code::__toString
     */
    public function test__toString()
    {
        $this->assertSame("THIS IS SAMPLE", $this->object->__toString());
    }
}
