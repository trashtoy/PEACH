<?php
class Peach_DF_JsonCodec_ArrayExprTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_ArrayExpr
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_ArrayExpr();
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
     * @covers Peach_DF_JsonCodec_ArrayExpr::__construct
     * @covers Peach_DF_JsonCodec_ArrayExpr::getResult
     */
    public function test__construct()
    {
        $expr = $this->object;
        $this->assertNull($expr->getResult());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_ArrayExpr::handle
     * @covers Peach_DF_JsonCodec_ArrayExpr::getResult
     */
    public function testHandleAndGetResult()
    {
        $context  = new Peach_DF_JsonCodec_Context('  [ 3.14 , true,  "test" ,null  ]   }  ', new Peach_Util_ArrayMap());
        $expected = array(3.14, true, "test", null);
        $expr     = $this->object;
        $expr->handle($context);
        $this->assertSame($expected, $expr->getResult());
        $this->assertSame("}", $context->current());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_ArrayExpr::handle
     * @covers Peach_DF_JsonCodec_ArrayExpr::getResult
     */
    public function testHandleEmpty()
    {
        $context = new Peach_DF_JsonCodec_Context('  [     ]   ,  ', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
        $this->assertSame(array(), $expr->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * 配列の末尾にカンマが存在する場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ArrayExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByLastComma()
    {
        $context = new Peach_DF_JsonCodec_Context(" [ 1, 3, 5, ]", new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * 値が来るべき場所で JSON が終わってしまった場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ArrayExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNoValue()
    {
        $context = new Peach_DF_JsonCodec_Context(" [ 1, 3, 5, ", new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * "]" または "," が来るべき場所で JSON が終わってしまった場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_ArrayExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNoStructuralChar()
    {
        $context = new Peach_DF_JsonCodec_Context(" [ 1, 3, 5 ", new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
}
