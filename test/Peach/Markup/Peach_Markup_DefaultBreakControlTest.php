<?php
class Peach_Markup_DefaultBreakControlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_DefaultBreakControl
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_Markup_DefaultBreakControl::getInstance();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * breaks() をテストします.
     * 以下を確認します.
     * 
     * - 子ノードを持たない要素の場合は false
     * - 子要素を一つだけ含み, それが整形済テキストの場合は true
     * - 子要素を一つだけ含み, それがコンテナ要素の場合, 再帰的にチェックした結果
     * - 子要素を一つだけ含み, 上記以外のノードの場合は false
     * - 子要素が二つ以上の場合は true
     * 
     * @covers Peach_Markup_DefaultBreakControl::breaks
     */
    public function testBreaks()
    {
        $obj = $this->object;
        
        $e1  = new Peach_Markup_ContainerElement("empty");
        $this->assertFalse($obj->breaks($e1));
        
        $e2  = new Peach_Markup_ContainerElement("pre");
        $e2->append(new Peach_Markup_Code("some script"));
        $this->assertTrue($obj->breaks($e2));
        
        $e3  = new Peach_Markup_ContainerElement("container");
        $e3->append($e1);
        $this->assertFalse($obj->breaks($e3));
        
        $e4  = new Peach_Markup_ContainerElement("container");
        $e4->append($e2);
        $this->assertTrue($obj->breaks($e4));
        
        $e5  = new Peach_Markup_ContainerElement("span");
        $e5->append("some text");
        $this->assertFalse($obj->breaks($e5));
        
        $e6  = new Peach_Markup_ContainerElement("span");
        $e6->append("first text");
        $e6->append("second test");
        $this->assertTrue($obj->breaks($e6));
    }
    
    /**
     * getInstance() をテストします.
     * 以下を確認します.
     * 
     * - 返り値が Peach_Markup_DefaultBreakControl のインスタンスである
     * - どの返り値も, 同一のインスタンスを返す
     * 
     * @covers Peach_Markup_DefaultBreakControl::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_Markup_DefaultBreakControl::getInstance();
        $obj2 = Peach_Markup_DefaultBreakControl::getInstance();
        $this->assertSame("Peach_Markup_DefaultBreakControl", get_class($obj1));
        $this->assertSame($obj1, $obj2);
    }
}
