<?php
class Peach_Markup_NameBreakControlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_NameBreakControl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_NameBreakControl(array("A", "B", "C"), array("X", "Y", "Z"));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Peach_Markup_NameBreakControl::breaks
     * @todo   Implement testBreaks().
     */
    public function testBreaks()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
