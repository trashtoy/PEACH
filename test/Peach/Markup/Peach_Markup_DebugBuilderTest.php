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
    
    /**
     * build() のテストです. 以下を確認します.
     * 
     * - ノードの構造をあらわすデバッグ文字列を返すこと
     * - 返り値と同じ文字列が自動的に echo されること
     * 
     * @covers Peach_Markup_DebugBuilder::__construct
     * @covers Peach_Markup_DebugBuilder::build
     * @covers Peach_Markup_DebugBuilder::createContext
     * @covers Peach_Markup_Context::handle
     */
    public function testBuild()
    {
        $builder  = $this->object;
        $node     = Peach_Markup_TestUtil::getTestNode();
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $this->expectOutputString($expected);
        $this->assertSame($expected, $builder->build($node));
    }
    
    /**
     * コンストラクタ引数に false を指定して DebugBuilder を初期化した場合,
     * 自動 echo がされないことを確認します.
     * 
     * @covers Peach_Markup_DebugBuilder::__construct
     * @covers Peach_Markup_DebugBuilder::build
     * @covers Peach_Markup_DebugBuilder::createContext
     * @covers Peach_Markup_Context::handle
     */
    public function testBuildNotEcho()
    {
        $builder  = new Peach_Markup_DebugBuilder(false);
        $node     = Peach_Markup_TestUtil::getTestNode();
        $expected = Peach_Markup_TestUtil::getDebugBuildResult();
        $this->expectOutputString("");
        $this->assertSame($expected, $builder->build($node));
    }
}
