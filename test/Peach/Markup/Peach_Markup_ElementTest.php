<?php
abstract class Peach_Markup_ElementTest extends PHPUnit_Framework_TestCase
{
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
     * @covers Peach_Markup_Element::getName
     * @todo   Implement testGetName().
     */
    abstract public function testGetName();

    /**
     * @covers Peach_Markup_Element::getAttribute
     * @todo   Implement testGetAttribute().
     */
    abstract public function testGetAttribute();

    /**
     * @covers Peach_Markup_Element::hasAttribute
     * @todo   Implement testHasAttribute().
     */
    abstract public function testHasAttribute();

    /**
     * @covers Peach_Markup_Element::setAttribute
     * @todo   Implement testSetAttribute().
     */
    abstract public function testSetAttribute();

    /**
     * @covers Peach_Markup_Element::setAttributes
     * @todo   Implement testSetAttributes().
     */
    abstract public function testSetAttributes();

    /**
     * @covers Peach_Markup_Element::removeAttribute
     * @todo   Implement testRemoveAttribute().
     */
    abstract public function testRemoveAttribute();

    /**
     * @covers Peach_Markup_Element::getAttributes
     * @todo   Implement testGetAttributes().
     */
    abstract public function testGetAttributes();
}
