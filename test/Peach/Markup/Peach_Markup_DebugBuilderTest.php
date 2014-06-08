<?php
require_once(__DIR__ . "/Peach_Markup_BuilderTest.php");
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_DebugBuilderTest extends Peach_Markup_BuilderTest
{
    /**
     * @var Peach_Markup_DebugBuilder
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_DebugBuilder();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    public function testBuild()
    {
        $builder  = $this->object;
        $node     = Peach_Markup_TestUtil::getTestNode();
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $this->expectOutputString($expected);
        $this->assertSame($expected, $builder->build($node));
    }
    
    public function testBuildNotEcho()
    {
        $builder  = new Peach_Markup_DebugBuilder(false);
        $node     = Peach_Markup_TestUtil::getTestNode();
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $this->expectOutputString("");
        $this->assertSame($expected, $builder->build($node));
    }
}
