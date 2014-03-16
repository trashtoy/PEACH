<?php
require_once(__DIR__ . "/Peach_Markup_BuilderTest.php");

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
     * getIndent() と setIndent() のテストです. 以下について確認します.
     * 
     * - 初期状態では getIndent() が null を返すこと
     * - setIndent() でセットした Indent オブジェクトが getIndent() で取得できること
     * 
     * @covers Peach_Markup_DefaultBuilder::getIndent
     * @covers Peach_Markup_DefaultBuilder::setIndent
     */
    public function testAccessIndent()
    {
        $builder = $this->object;
        $this->assertNull($builder->getIndent());
        
        $subject = new Peach_Markup_Indent(-1, Peach_Markup_Indent::TAB, Peach_Markup_Indent::LF);
        $builder->setIndent($subject);
        $this->assertSame($subject, $builder->getIndent());
    }
    
    /**
     * getRenderer() と setRenderer() のテストです. 以下について確認します.
     * 
     * - 初期状態では getRenderer() が null を返すこと
     * - setRenderer() でセットした Renderer オブジェクトが getRenderer() で取得できること
     * 
     * @covers Peach_Markup_DefaultBuilder::getRenderer
     * @covers Peach_Markup_DefaultBuilder::setRenderer
     */
    public function testAccessRenderer()
    {
        $builder = $this->object;
        $this->assertNull($builder->getRenderer());
        
        $subject = Peach_Markup_XmlRenderer::getInstance();
        $builder->setRenderer($subject);
        $this->assertSame($subject, $builder->getRenderer());
    }
    
    /**
     * getBreakControl() と setBreakControl() のテストです. 以下について確認します.
     * 
     * - 初期状態では getBreakControl() が null を返すこと
     * - setBreakControl() でセットした BreakControl オブジェクトが getBreakControl() で取得できること
     * 
     * @covers Peach_Markup_DefaultBuilder::setBreakControl
     * @covers Peach_Markup_DefaultBuilder::setBreakControl
     */
    public function testAccessBreakControl()
    {
        $builder = $this->object;
        $this->assertNull($builder->getBreakControl());
        
        $subject = new Peach_Markup_NameBreakControl(array(), array());
        $builder->setBreakControl($subject);
        $this->assertSame($subject, $builder->getBreakControl());
    }
    
    /**
     * @covers Peach_Markup_BuilderTest::build
     * @todo   Implement testBuild()
     */
    public function testBuild()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
}
