<?php
class Peach_Markup_BreakControlWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_BreakControlWrapper
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_BreakControlWrapper();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * getOriginal() のテストです. 以下を確認します.
     * 
     * - コンストラクタの引数にしたものと同じオブジェクトを返すこと
     * - コンストラクタの引数を省略した場合は DefaultBreakControl を返すこと
     * 
     * @covers Peach_Markup_BreakControlWrapper::__construct
     * @covers Peach_Markup_BreakControlWrapper::getOriginal
     */
    public function testGetOriginal()
    {
        $original = Peach_Markup_DefaultBreakControl::getInstance();
        $wrapper  = new Peach_Markup_BreakControlWrapper($original);
        $this->assertSame($original, $wrapper->getOriginal());
        
        $defaultObj = new Peach_Markup_BreakControlWrapper();
        $this->assertSame($original, $defaultObj->getOriginal());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_Markup_BreakControlWrapper::breaks
     */
    public function testBreaks()
    {
        $obj = $this->object;
        $e5  = new Peach_Markup_ContainerElement("span");
        $e5->append("some text");
        $this->assertFalse($obj->breaks($e5));
        
        $e6  = new Peach_Markup_ContainerElement("span");
        $e6->append("first text");
        $e6->append("second test");
        $this->assertTrue($obj->breaks($e6));
    }
}
