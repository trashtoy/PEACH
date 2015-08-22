<?php
class Peach_DF_JsonCodec_ValueTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_Value
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_Value();
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
     * @covers Peach_DF_JsonCodec_Value::__construct
     */
    public function test__construct()
    {
        $value = new Peach_DF_JsonCodec_Value();
        $this->assertNull($value->getResult());
    }
    
    /**
     * リテラル "true" の解析テストです. 以下を確認します.
     * 
     * - "true" を decode した結果, 真偽値 TRUE に変換されること
     * - 引数の Context の index が 4 進むこと
     * 
     * @covers Peach_DF_JsonCodec_Value::handle
     * @covers Peach_DF_JsonCodec_Value::decodeLiteral
     * @covers Peach_DF_JsonCodec_Value::getResult
     */
    public function testHandleTrue()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("true}", new Peach_Util_ArrayMap());
        $value->handle($context);
        $this->assertSame(true, $value->getResult());
        $this->assertSame("}", $context->current());
    }
    
    /**
     * リテラル "false" の解析テストです. 以下を確認します.
     * 
     * - "false" を decode した結果, 真偽値 FALSE に変換されること
     * - 引数の Context の index が 5 進むこと
     * 
     * @covers Peach_DF_JsonCodec_Value::handle
     * @covers Peach_DF_JsonCodec_Value::decodeLiteral
     * @covers Peach_DF_JsonCodec_Value::getResult
     */
    public function testHandleFalse()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("false,", new Peach_Util_ArrayMap());
        $value->handle($context);
        $this->assertSame(false, $value->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * リテラル "null" の解析テストです. 以下を確認します.
     * 
     * - "null" を decode した結果, NULL に変換されること
     * - 引数の Context の index が 4 進むこと
     * 
     * @covers Peach_DF_JsonCodec_Value::handle
     * @covers Peach_DF_JsonCodec_Value::decodeLiteral
     * @covers Peach_DF_JsonCodec_Value::getResult
     */
    public function testHandleNull()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("null{", new Peach_Util_ArrayMap());
        $value->handle($context);
        $this->assertNull($value->getResult());
        $this->assertSame("{", $context->current());
    }
    
    /**
     * 文字列の解析テストです. 以下を確認します.
     * 
     * - 二重引用符で囲まれた文字列が結果として返されること
     * - 引数の Context の index が文字列長 + 2 だけ進むこと
     */
    public function testHandleString()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context('"Test",', new Peach_Util_ArrayMap());
        $value->handle($context);
        $this->assertSame("Test", $value->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * 数値の解析テストです. 以下を確認します.
     * 
     * - その数値文字列が表現する値に等しい結果を返すこと
     * - 引数の Context の index が文字列長だけ進むこと
     */
    public function testHandleNumber()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("3e+5,", new Peach_Util_ArrayMap());
        $value->handle($context);
        $this->assertSame(300000.0, $value->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * 配列の解析テストです. 以下を確認します.
     * 
     * - その配列形式の文字列が表現する値に等しい結果を返すこと
     * - 引数の Context の index が文字列長だけ進むこと
     */
    public function testHandleArray()
    {
        $value    = $this->object;
        $context  = new Peach_DF_JsonCodec_Context("[1, 2, 3, [4, 5], 6, 7, [8]] }", new Peach_Util_ArrayMap());
        $expected = array(1, 2, 3, array(4, 5), 6, 7, array(8));
        $value->handle($context);
        $this->assertSame($expected, $value->getResult());
        $this->assertSame("}", $context->current());
    }
    
    /**
     * Object の解析テストです. 以下を確認します.
     * 
     * - その object 形式の文字列が表現する値に等しい結果を返すこと
     * - 引数の Context の index が文字列長だけ進むこと
     */
    public function testHandleObject()
    {
        $value    = $this->object;
        $context  = new Peach_DF_JsonCodec_Context('{ "a" : true, "b" : [ -123, 3.5E+7, 0 ], "c" : { "x" : "asdf", "y" : "hoge" }, "d" : null} , ', new Peach_Util_ArrayMap());
        
        $expected = new stdClass();
        $expected->a = true;
        $expected->b = array(-123, 3.5e+7, 0);
        $expected->c = new stdClass();
        $expected->c->x = "asdf";
        $expected->c->y = "hoge";
        $expected->d = null;
        
        $value->handle($context);
        $this->assertEquals($expected, $value->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * 不正な文字列を検知した場合に DecodeException をスローすることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Value::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFail()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("asdf", new Peach_Util_ArrayMap());
        $value->handle($context);
    }
    
    /**
     * リテラルの解析中に不正な文字列を検知した場合に DecodeException をスローすることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Value::handle
     * @covers Peach_DF_JsonCodec_Value::decodeLiteral
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testDecodeLiteralFail()
    {
        $value   = $this->object;
        $context = new Peach_DF_JsonCodec_Context("trap", new Peach_Util_ArrayMap());
        $value->handle($context);
    }
}
