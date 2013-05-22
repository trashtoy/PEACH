<?php
require_once(__DIR__ . "/Peach_Markup_AbstractRendererTest.php");

class Peach_Markup_SgmlRendererTest extends Peach_Markup_AbstractRendererTest
{
    /**
     * @var Peach_Markup_SgmlRenderer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Markup_SgmlRenderer::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Peach_Markup_SgmlRenderer::getInstance
     * @todo   Implement testGetInstance().
     */
    public function testGetInstance()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testFormatCdataSection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testFormatEmptyTag()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testFormatEndTag()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    public function testFormatStartTag()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
