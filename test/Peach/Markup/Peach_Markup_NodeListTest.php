<?php
require_once(__DIR__ . "/Peach_Markup_TestContext.php");
require_once(__DIR__ . "/Peach_Markup_ContainerTestImpl.php");

class Peach_Markup_NodeListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_NodeList
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_NodeList();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタのテストです. 以下を確認します.
     * 
     * - 引数なしで初期化した場合, 子ノードを持たないインスタンスが生成されること
     * - 引数を指定して初期化した場合, 引数のノードを子ノードとして持つインスタンスが生成されること
     * 
     * @covers Peach_Markup_NodeList::__construct
     */
    public function test__construct()
    {
        $l1 = new Peach_Markup_NodeList();
        $this->assertSame(array(), $l1->getChildNodes());
        
        $l2         = new Peach_Markup_NodeList(array("foo", "bar", "baz"));
        $childNodes = $l2->getChildNodes();
        $this->assertEquals(new Peach_Markup_Text("baz"), $childNodes[2]);
    }
    
    /**
     * Container で定義されている append() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_NodeList::append
     * @covers Peach_Markup_NodeList::getAppendee
     * @see    Peach_Markup_ContainerTestImpl::testAppend
     */
    public function testAppend()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testAppend();
    }
    
    /**
     * 自分自身を含むノードを append しようとした場合に例外をスローすることを確認します.
     * 
     * @covers Peach_Markup_NodeList::append
     * @covers Peach_Markup_NodeList::checkOwner
     * @expectedException InvalidArgumentException
     */
    public function testAppendFail()
    {
        $a        = new Peach_Markup_ContainerElement("a");
        $b        = new Peach_Markup_ContainerElement("b");
        $c        = new Peach_Markup_ContainerElement("c");
        $nodeList = new Peach_Markup_NodeList(array($a, $b, $c));
        $c->append($nodeList);
    }
    
    /**
     * Context の handleNodeList() が呼び出されることを確認します.
     * 
     * @covers Peach_Markup_NodeList::accept
     */
    public function testAccept()
    {
        $context = new Peach_Markup_TestContext();
        $this->object->accept($context);
        $this->assertSame("handleNodeList", $context->getResult());
    }
    
    /**
     * 追加されたノードの個数を返すことを確認します.
     * 
     * @covers Peach_Markup_NodeList::size
     */
    public function testSize()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testSize();
    }
    
    /**
     * Container で定義されている getChildNodes() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_NodeList::getChildNodes
     * @see    Peach_Markup_ContainerTestImpl::testChildNodes
     */
    public function testGetChildNodes()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object);
        $test->testGetChildNodes();
    }
}
