<?php
require_once(__DIR__ . "/Peach_Markup_ContextTest.php");
class Peach_Markup_DebugContextTest extends Peach_Markup_ContextTest
{
    /**
     * @var Peach_Markup_DebugContext
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object   = new Peach_Markup_DebugContext();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers Peach_Markup_DebugContext::getResult
     * @todo   Implement testGetResult().
     */
    public function testGetResult()
    {

        $context = new Peach_Markup_DebugContext();
    }
    
    /**
     * @covers Peach_Markup_DebugContext::handleComment
     * @todo   Implement testHandleComment().
     */
    public function testHandleComment()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Peach_Markup_DebugContext::handleContainerElement
     * @todo   Implement testHandleContainerElement().
     */
    public function testHandleContainerElement()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Peach_Markup_DebugContext::handleEmptyElement
     * @todo   Implement testHandleEmptyElement().
     */
    public function testHandleEmptyElement()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Peach_Markup_DebugContext::handleNodeList
     * @todo   Implement testHandleNodeList().
     */
    public function testHandleNodeList()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Peach_Markup_DebugContext::handleText
     * @todo   Implement testHandleText().
     */
    public function testHandleText()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Peach_Markup_DebugContext::handleCode
     * @todo   Implement testHandleCode().
     */
    public function testHandleCode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
