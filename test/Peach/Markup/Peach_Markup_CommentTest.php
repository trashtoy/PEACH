<?php
require_once(__DIR__ . "/Peach_Markup_ContainerTestImpl.php");

class Peach_Markup_CommentTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Comment
     */
    protected $object1;
    
    /**
     * @var Peach_Markup_Comment
     */
    protected $object2;
    
    /**
     * @var Peach_Markup_Comment
     */
    protected $object3;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object1 = new Peach_Markup_Comment();
        $this->object2 = new Peach_Markup_comment("test-prefix");
        $this->object3 = new Peach_Markup_Comment("test-prefix", "test-suffix");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * getPrefix() をテストします.
     * 以下を確認します.
     * 
     * - コンストラクタ引数を省略した場合は空文字列となる
     * - コンストラクタの第一引数に指定した文字列を返す
     * 
     * @covers Peach_Markup_Comment::getPrefix
     */
    public function testGetPrefix()
    {
        $this->assertSame("",            $this->object1->getPrefix());
        $this->assertSame("test-prefix", $this->object2->getPrefix());
        $this->assertSame("test-prefix", $this->object3->getPrefix());
    }
    
    /**
     * getSuffix() をテストします.
     * 以下を確認します.
     * 
     * - コンストラクタの第二引数を省略した場合は空文字列となる
     * - コンストラクタの第二引数に指定した文字列を返す
     * 
     * @covers Peach_Markup_Comment::getSuffix
     * @todo   Implement testGetSuffix().
     */
    public function testGetSuffix()
    {
        $this->assertSame("",            $this->object1->getSuffix());
        $this->assertSame("",            $this->object2->getSuffix());
        $this->assertSame("test-suffix", $this->object3->getSuffix());
    }
    
    /**
     * Context の handleComment() が呼び出されることを確認します.
     * 
     * @covers Peach_Markup_Comment::accept
     */
    public function testAccept()
    {
        $obj   = $this->object1;
        $debug = new Peach_Markup_DebugContext(false);
        $obj->accept($debug);
        $this->assertSame("Comment {\r\n}\r\n", $debug->getResult());
    }
    
    /**
     * Container で定義されている append() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_Comment::append
     * @see    Peach_Markup_ContainerTestImpl::testAppend
     */
    public function testAppend()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object1);
        $test->testAppend();
    }
    
    /**
     * Container で定義されている getChildNodes() の仕様通りに動作することを確認します.
     * 
     * @covers Peach_Markup_Comment::getChildNodes
     * @see    Peach_Markup_ContainerTestImpl::testChildNodes
     */
    public function testGetChildNodes()
    {
        $test = new Peach_Markup_ContainerTestImpl($this, $this->object1);
        $test->testGetChildNodes();
    }
}
