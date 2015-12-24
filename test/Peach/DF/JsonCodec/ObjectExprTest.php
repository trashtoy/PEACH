<?php
class Peach_DF_JsonCodec_ObjectExprTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_ObjectExpr
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_ObjectExpr();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * インスタンス化直後は $result が null となっていることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ObjectExpr::__construct
     * @covers Peach_DF_JsonCodec_ObjectExpr::getResult
     */
    public function test__construct()
    {
        $expr = $this->object;
        $this->assertNull($expr->getResult());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_ObjectExpr::handle
     * @covers Peach_DF_JsonCodec_ObjectExpr::getResult
     */
    public function testHandleAndGetResult()
    {
        $context  = new Peach_DF_JsonCodec_Context('{ "a" : -3.14, "b": [true, false, true],"c" : "xxxx", "d": null  }   ,', new Peach_Util_ArrayMap());
        $expr     = $this->object;
        $expected = new stdClass();
        $expected->a = -3.14;
        $expected->b = array(true, false, true);
        $expected->c = "xxxx";
        $expected->d = null;
        
        $expr->handle($context);
        $this->assertEquals($expected, $expr->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_ObjectExpr::handle
     * @covers Peach_DF_JsonCodec_ObjectExpr::getResult
     */
    public function testHandleEmpty()
    {
        $context  = new Peach_DF_JsonCodec_Context("    {\r\n    }   ,   ", new Peach_Util_ArrayMap());
        $expr     = $this->object;
        $expr->handle($context);
        $this->assertEquals(new stdClass(), $expr->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * オブジェクトのキーが文字列でない場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ObjectExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNotStringKey()
    {
        $context  = new Peach_DF_JsonCodec_Context('{ test : "invalid" }', new Peach_Util_ArrayMap());
        $expr     = $this->object;
        $expr->handle($context);
    }
    
    /**
     * "}" の直前に "," がある場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ObjectExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     * @expectedExceptionMessage Closing bracket after comma is not permitted at line 1, column 33
     */
    public function testHandleFailByCommaEnding()
    {
        $context  = new Peach_DF_JsonCodec_Context('{ "a" : 1 , "b" : 2 , "c" : 3 , }', new Peach_Util_ArrayMap());
        $expr     = $this->object;
        $expr->handle($context);
    }
}
