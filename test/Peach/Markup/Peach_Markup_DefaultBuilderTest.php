<?php
require_once(__DIR__ . "/Peach_Markup_BuilderTest.php");
require_once(__DIR__ . "/Peach_Markup_TestUtil.php");

class Peach_Markup_DefaultBuilderTest extends Peach_Markup_BuilderTest
{
    /**
     * @var Peach_Markup_DefaultBuilder
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_Markup_DefaultBuilder();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * インスタンス生成直後はすべてのフィールドが null となっていることを確認します.
     * @covers Peach_Markup_DefaultBuilder::__construct
     */
    public function test__construct()
    {
        $obj = new Peach_Markup_DefaultBuilder();
        $this->assertNull($obj->getBreakControl());
        $this->assertNull($obj->getIndent());
        $this->assertNull($obj->getRenderer());
    }
    
    /**
     * getIndent() と setIndent() のテストです. 以下について確認します.
     * 
     * - 初期状態では getIndent() が null を返すこと
     * - setIndent() でセットした Indent オブジェクトが getIndent() で取得できること
     * - null を指定するとセットした Indent オブジェクトが解除されること
     * 
     * @covers Peach_Markup_DefaultBuilder::getIndent
     * @covers Peach_Markup_DefaultBuilder::setIndent
     * @covers Peach_Markup_DefaultBuilder::initRenderer
     */
    public function testAccessIndent()
    {
        $builder = $this->object;
        $this->assertNull($builder->getIndent());
        
        $subject = new Peach_Markup_Indent(-1, Peach_Markup_Indent::TAB, Peach_Markup_Indent::LF);
        $builder->setIndent($subject);
        $this->assertSame($subject, $builder->getIndent());
        
        $builder->setIndent(null);
        $this->assertNull($builder->getIndent());
    }
    
    /**
     * getRenderer() と setRenderer() のテストです. 以下について確認します.
     * 
     * - 初期状態では getRenderer() が null を返すこと
     * - setRenderer() でセットした Renderer オブジェクトが getRenderer() で取得できること
     * - null を指定するとセットした Renderer オブジェクトが解除されること
     * - 不正な値をセットした場合は InvalidArgumentException をスローすること
     * 
     * @covers Peach_Markup_DefaultBuilder::getRenderer
     * @covers Peach_Markup_DefaultBuilder::setRenderer
     * @covers Peach_Markup_DefaultBuilder::initRenderer
     */
    public function testAccessRenderer()
    {
        $builder = $this->object;
        $this->assertNull($builder->getRenderer());
        
        $subject = Peach_Markup_XmlRenderer::getInstance();
        $builder->setRenderer($subject);
        $this->assertSame($subject, $builder->getRenderer());
        
        $builder->setRenderer(null);
        $this->assertNull($builder->getRenderer());
        
        $builder->setRenderer("xhtml");
        $this->assertSame($subject, $builder->getRenderer());
        $builder->setRenderer("sgml");
        $this->assertSame(Peach_Markup_SgmlRenderer::getInstance(), $builder->getRenderer());
        
        $this->setExpectedException("InvalidArgumentException");
        $builder->setRenderer("InvalidValue");
    }
    
    /**
     * getBreakControl() と setBreakControl() のテストです. 以下について確認します.
     * 
     * - 初期状態では getBreakControl() が null を返すこと
     * - setBreakControl() でセットした BreakControl オブジェクトが getBreakControl() で取得できること
     * - null を指定するとセットした BreakControl オブジェクトが解除されること
     * 
     * @covers Peach_Markup_DefaultBuilder::getBreakControl
     * @covers Peach_Markup_DefaultBuilder::setBreakControl
     */
    public function testAccessBreakControl()
    {
        $builder = $this->object;
        $this->assertNull($builder->getBreakControl());
        
        $subject = new Peach_Markup_NameBreakControl(array(), array());
        $builder->setBreakControl($subject);
        $this->assertSame($subject, $builder->getBreakControl());
        
        $builder->setBreakControl(null);
        $this->assertNull($builder->getBreakControl());
    }
    
    /**
     * Builder にセットした設定が, build 時に適用されることを確認します.
     * @todo   BreakControl が適用されるかどうかのテスト
     * @covers Peach_Markup_DefaultBuilder::createContext
     * @covers Peach_Markup_Builder::build
     */
    public function testBuild()
    {
        $builder = $this->object;
        $node    = Peach_Markup_TestUtil::getTestNode();
        $this->assertSame(Peach_Markup_TestUtil::getDefaultBuildResult(), $builder->build($node));
        
        $builder->setIndent(new Peach_Markup_Indent(0, "  ", Peach_Markup_Indent::LF));
        $builder->setRenderer("HTML");
        $this->assertSame(Peach_Markup_TestUtil::getCustomBuildResult(), $builder->build($node));
    }
}
