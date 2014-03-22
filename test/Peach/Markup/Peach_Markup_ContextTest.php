<?php
abstract class Peach_Markup_ContextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Context
     */
    protected $object;
    
    /**
     * @var Peach_Markup_Node
     */
    protected $testNode;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers Peach_Markup_Context::handle
     * @covers Peach_Markup_Context::getResult
     */
    public abstract function testGetResult();
}
