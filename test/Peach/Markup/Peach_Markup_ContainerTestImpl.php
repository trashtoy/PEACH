<?php
class Peach_Markup_ContainerTestImpl
{
    /**
     * @var Peach_Markup_Container
     */
    private $object;
    
    /**
     * @var PHPUnit_Framework_TestCase
     */
    protected $test;
    
    public function __construct(PHPUnit_Framework_TestCase $test, Peach_Markup_Container $object)
    {
        $this->test   = $test;
        $this->object = $object;
    }
    
    /**
     * append() をテストします.
     * 以下を確認します.
     * 
     * - 任意のスカラー値および Node が追加できること
     * - null および None を指定した場合, 変化がない (追加されない) こと
     * - コンテナを追加した場合, 自身ではなくその子ノードが追加されること
     * 
     * @covers Peach_Markup_Container::append
     */
    public function testAppend()
    {
        $nList = new Peach_Markup_NodeList();
        $nList->append("foo");
        $nList->append("bar");
        $nList->append("baz");
        
        $test  = $this->test;
        $obj   = $this->object;
        $obj->append(null);
        $obj->append("TEXT");
        $obj->append(new Peach_Markup_EmptyElement("test"));
        $obj->append(Peach_Markup_None::getInstance()); // added none
        $obj->append($nList); // added 3 nodes
        $test->assertSame(5, count($obj->getChildNodes()));
    }
    
    /**
     * getChildNodes() をテストします.
     * 以下を確認します.
     * 
     * - 初期状態では空の配列を返すこと
     * - Comment ノードに append した一覧を返すこと
     * 
     * @covers Peach_Markup_Container::getChildNodes
     */
    public function testGetChildNodes()
    {
        $test  = $this->test;
        $obj   = $this->object;
        $test->assertSame(array(), $obj->getChildNodes());
        $obj->append("SAMPLE TEXT 1");
        $obj->append(new Peach_Markup_Code("SAMPLE CODE 2"));
        $obj->append(new Peach_Markup_Text("SAMPLE TEXT 3"));
        
        $children = $obj->getChildNodes();
        $test->assertSame(3, count($children));
        $test->assertSame("Peach_Markup_Code", get_class($children[1]));
    }
    
    /**
     * 追加されたノードの個数を返すことを確認します.
     * 
     * @covers Peach_Markup_ContainerElement::size
     * @covers Peach_Markup_NodeList::size
     */
    public function testSize()
    {
        $obj   = $this->object;
        $test  = $this->test;
        $test->assertSame(0, $obj->size());
        $obj->append("TEST");
        $obj->append(new Peach_Markup_EmptyElement("sample"));
        $obj->append(array("first", "second", "third"));
        $test->assertSame(5, $obj->size());
    }
}
