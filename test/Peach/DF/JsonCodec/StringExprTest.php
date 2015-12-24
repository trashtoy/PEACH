<?php
class Peach_DF_JsonCodec_StringExprTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_StringExpr
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_StringExpr();
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
     * @covers Peach_DF_JsonCodec_StringExpr::__construct
     * @covers Peach_DF_JsonCodec_StringExpr::getResult
     */
    public function test__construct()
    {
        $expr = new Peach_DF_JsonCodec_StringExpr();
        $this->assertNull($expr->getResult());
    }
    
    /**
     * 文字列 '""' の解析結果が空文字列となることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @covers Peach_DF_JsonCodec_StringExpr::getResult
     */
    public function testHandleEmptyString()
    {
        $expr    = $this->object;
        $context = new Peach_DF_JsonCodec_Context('"":', new Peach_Util_ArrayMap());
        $expr->handle($context);
        $this->assertSame("", $expr->getResult());
        $this->assertSame(":", $context->current());
    }
    
    /**
     * エスケープシーケンスを含んだ文字列が, エスケープ解除された状態で decode されることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @covers Peach_DF_JsonCodec_StringExpr::validateCodePoint
     * @covers Peach_DF_JsonCodec_StringExpr::decodeEscapedChar
     * @covers Peach_DF_JsonCodec_StringExpr::getResult
     */
    public function testHandle()
    {
        $q  = chr(0x22); // "
        $rs = chr(0x5C); // \
        $sl = chr(0x2F); // /
        $b  = chr(0x8);  // backspace
        $f  = chr(0xC);  // form feed
        $n  = chr(0xA);  // LF
        $r  = chr(0xD);  // CR
        $t  = chr(0x9);  // TAB
        $a  = chr(227) . chr(129) . chr(130); // "あ" (U+3042)
        
        $context = new Peach_DF_JsonCodec_Context('"Test\\"\\\\\\/\\b\\f\\n\\r\\t\\u3042End",', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
        $this->assertSame("Test{$q}{$rs}{$sl}{$b}{$f}{$n}{$r}{$t}{$a}End", $expr->getResult());
        $this->assertSame(",", $context->current());
    }
    
    /**
     * 存在しないエスケープシーケンスを decode した場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @covers Peach_DF_JsonCodec_StringExpr::decodeEscapedChar
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testDecodeEscapedCharFail()
    {
        $context = new Peach_DF_JsonCodec_Context('"Test\\n\\xEnd"', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * 不正な 16 進数エスケープ (\uXXXX) を decode した場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @covers Peach_DF_JsonCodec_StringExpr::decodeEscapedChar
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleHexadecimalSequenceFail()
    {
        $context = new Peach_DF_JsonCodec_Context('"Test\\uasdfEnd"', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * 二重引用符で始まっていない文字列を decode した場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleInvalidStartingFail()
    {
        $context = new Peach_DF_JsonCodec_Context("Invalid", new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * 二重引用符で閉じられていない文字列を decode した場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleUnfinishedStringFail()
    {
        $context = new Peach_DF_JsonCodec_Context('"Unfinished', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * 文字列中に %x00-%x1F の範囲にあたる文字が含まれていた場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StringExpr::handle
     * @covers Peach_DF_JsonCodec_StringExpr::validateCodePoint
     * @expectedException Peach_DF_JsonCodec_DecodeException
     * @expectedExceptionMessage Unicode code point %x0a is not allowed for string at line 1, column 6
     */
    public function testHandleInvalidAsciiFail()
    {
        $context = new Peach_DF_JsonCodec_Context("\"This\nis test\"", new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
}
