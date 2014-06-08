<?php
class Peach_Markup_MinimalBreakControlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_MinimalBreakControl
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Markup_MinimalBreakControl::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * false を返すことを確認します.
     * @covers Peach_Markup_MinimalBreakControl::breaks
     */
    public function testBreaks()
    {
        $node = new Peach_Markup_ContainerElement("span");
        $node->append("first text");
        $node->append("second test");
        $this->assertFalse($this->object->breaks($node));
    }
    
    /**
     * getInstance() をテストします.
     * 以下を確認します.
     * 
     * - 返り値が Peach_Markup_MinimalBreakControl のインスタンスである
     * - どの返り値も, 同一のインスタンスを返す
     * 
     * @covers Peach_Markup_DefaultBreakControl::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_Markup_MinimalBreakControl::getInstance();
        $obj2 = Peach_Markup_MinimalBreakControl::getInstance();
        $this->assertSame("Peach_Markup_MinimalBreakControl", get_class($obj1));
        $this->assertSame($obj1, $obj2);
    }
}
