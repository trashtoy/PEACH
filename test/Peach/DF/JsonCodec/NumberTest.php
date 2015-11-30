<?php
class Peach_DF_JsonCodec_NumberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_Number
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_Number();
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * インスタンス化直後は getResult() の結果が 0 となっていることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Number::__construct
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function test__construct()
    {
        $expr = new Peach_DF_JsonCodec_Number();
        $this->assertSame(0, $expr->getResult());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleIntegralPart
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleZero()
    {
        $this->checkHandleByString("0", 0);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleIntegralPart
     * @covers Peach_DF_JsonCodec_Number::handleFirstDigit
     * @covers Peach_DF_JsonCodec_Number::handleDigitSequence
     * @covers Peach_DF_JsonCodec_Number::handleFractionPart
     * @covers Peach_DF_JsonCodec_Number::handleExponentPart
     * @covers Peach_DF_JsonCodec_Number::checkDigit
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandlePositiveInt()
    {
        $this->checkHandleByString("135", 135);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleMinus
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleNegativeInt()
    {
        $this->checkHandleByString("-100", -100);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleFractionPart
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleDecimal()
    {
        $this->checkHandleByString("0.0625", 0.0625);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleExponentPart
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleExpPlus()
    {
        $this->checkHandleByString("713.5E+5", 71350000.0);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleExponentPart
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleExpMinus()
    {
        $this->checkHandleByString("15625E-6", 0.015625);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleExponentPart
     * @covers Peach_DF_JsonCodec_Number::getResult
     */
    public function testHandleExpBig()
    {
        $this->checkHandleByString("1.5E15", 1.5E15);
    }
    
    /**
     * 0 から始まる整数部を持つ数値表現がエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleIntegralPart
     * @covers Peach_DF_JsonCodec_Number::handleFirstDigit
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleZeroStartingNumberFail()
    {
        $expr    = $this->object;
        $context = new Peach_DF_JsonCodec_Context("0123", new Peach_Util_ArrayMap());
        $expr->handle($context);
    }
    
    /**
     * 小数の表記が正しくない ("." の後に数字がない) 場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleFractionPart
     * @covers Peach_DF_JsonCodec_Number::handleFirstDigit
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleNoFracFail()
    {
        $expr    = $this->object;
        $context = new Peach_DF_JsonCodec_Context("3.xyz", new Peach_Util_ArrayMap());
        $expr->handle($context);
    }
    
    /**
     * 指数部の表記が正しくない ("e" の後に数字がない) 場合にエラーとなることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Number::handle
     * @covers Peach_DF_JsonCodec_Number::handleExponentPart
     * @covers Peach_DF_JsonCodec_Number::handleFirstDigit
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleNoExponentNumberFail()
    {
        $expr    = $this->object;
        $context = new Peach_DF_JsonCodec_Context("1.0exyz", new Peach_Util_ArrayMap());
        $expr->handle($context);
    }
    
    /**
     * オプション BIGINT_AS_STRING のテストです. 以下を確認します.
     * 
     * - マイナス 2 の 32 乗以上 2 の 32 乗未満の整数は常に整数型に変換すること
     * - オプションが ON の場合, 巨大整数を整数として変換すること
     * - オプションが OFF の場合, 巨大整数を float として変換すること
     * 
     * @covers Peach_DF_JsonCodec_Number::handle
     */
    public function testHandleNumberByBigInt()
    {
        $posSmall = "54321";
        $negSmall = "-54321";
        $posBig   = "1234567890123456";
        $negBig   = "-1234567890123456";
        
        $this->checkHandleByString($posSmall, 54321, false);
        $this->checkHandleByString($posSmall, 54321, true);
        $this->checkHandleByString($negSmall, -54321, false);
        $this->checkHandleByString($negSmall, -54321, true);
        $this->checkHandleByString($posBig, 1234567890123456, false);
        $this->checkHandleByString($posBig, "1234567890123456", true);
        $this->checkHandleByString($negBig, -1234567890123456, false);
        $this->checkHandleByString($negBig, "-1234567890123456", true);
    }
    
    /**
     * handle のテストです. 以下を確認します.
     * 
     * - 第 1 引数の文字列を持つ Context を handle した結果, 第 2 引数の値が得られること
     * - Context の現在位置が 第 1 引数の長さ分だけ進められること
     * 
     * @param string $str      Context が持つ文字列
     * @param int    $expected 得られるはずの値
     * @param bool   $bigInt   オプション BIGINT_AS_STRING を ON にする場合は true
     */
    private function checkHandleByString($str, $expected, $bigInt = false)
    {
        $opt = new Peach_Util_ArrayMap();
        if ($bigInt) {
            $opt->put(Peach_DF_JsonCodec::BIGINT_AS_STRING, true);
        }
        
        $expr    = new Peach_DF_JsonCodec_Number();
        $context = new Peach_DF_JsonCodec_Context("{$str},", $opt);
        $expr->handle($context);
        $this->assertSame($expected, $expr->getResult());
        $this->assertSame(",", $context->current());
    }
}
