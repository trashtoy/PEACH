<?php
require_once(__DIR__ . "/Peach_Markup_TestContext.php");

class Peach_Markup_NoneTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_None
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Markup_None::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * getInstance() のテストです.
     * 
     * - Peach_Markup_None のインスタンスを返すことを確認します.
     * - 常に同一のオブジェクトを返すことを確認します.
     * 
     * @covers Peach_Markup_None::getInstance
     */
    public function testGetInstance()
    {
        $obj = Peach_Markup_None::getInstance();
        $this->assertSame("Peach_Markup_None", get_class($obj));
        $this->assertSame($this->object, $obj);
    }
    
    /**
     * Context の handleNone() が呼び出されることを確認します.
     * 
     * @covers Peach_Markup_None::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleNone", $context->getResult());
    }
}
