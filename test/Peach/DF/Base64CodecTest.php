<?php
class Peach_DF_Base64CodecTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_Base64Codec
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = Peach_DF_Base64Codec::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * getInstance() のテストです. 以下を確認します.
     * 
     * - Peach_DF_Base64Codec クラスのインスタンスを返す
     * - 同じ引数で実行した場合, 常に同一のオブジェクトを返す
     * - 引数を省略した場合は false を指定した場合と同じように扱われる
     * 
     * @covers Peach_DF_Base64Codec::__construct
     * @covers Peach_DF_Base64Codec::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_DF_Base64Codec::getInstance(false);
        $obj2 = Peach_DF_Base64Codec::getInstance(false);
        $obj3 = Peach_DF_Base64Codec::getInstance(true);
        $obj4 = Peach_DF_Base64Codec::getInstance(true);
        $obj5 = Peach_DF_Base64Codec::getInstance();

        $this->assertSame("Peach_DF_Base64Codec", get_class($obj1));
        $this->assertSame($obj1, $obj2);
        $this->assertSame($obj3, $obj4);
        $this->assertNotEquals($obj1, $obj3);
        $this->assertSame($obj1, $obj5);
    }

    /**
     * base64 でエンコードされた文字列をデコードできることを確認します.
     * 
     * @covers Peach_DF_Base64Codec::decode
     */
    public function testDecode()
    {
        $obj      = $this->object;
        $test     = "VGhlIFF1aWNrIEJyb3duIEZveCBKdW1wcyBPdmVyIFRoZSBMYXp5IERvZ3Mu";
        $expected = "The Quick Brown Fox Jumps Over The Lazy Dogs.";
        $this->assertSame($expected, $obj->decode($test));
    }

    /**
     * strict モードが ON の場合, 不正な文字を含む base64 文字列のデコード結果が
     * false になることを確認します.
     * 
     * @covers Peach_DF_Base64Codec::decode
     */
    public function testDecodeWithStrict()
    {
        $obj1 = Peach_DF_Base64Codec::getInstance(false);
        $obj2 = Peach_DF_Base64Codec::getInstance(true);
        $test = "SGVs,bG8g,V29y,bGQh";
        $this->assertSame("Hello World!", $obj1->decode($test));
        $this->assertFalse($obj2->decode($test));
    }

    /**
     * 任意の文字列を base64 でエンコードできることを確認します.
     * 
     * @covers Peach_DF_Base64Codec::encode
     */
    public function testEncode()
    {
        $obj      = $this->object;
        $expected = "VGhlIFF1aWNrIEJyb3duIEZveCBKdW1wcyBPdmVyIFRoZSBMYXp5IERvZ3Mu";
        $test     = "The Quick Brown Fox Jumps Over The Lazy Dogs.";
        $this->assertSame($expected, $obj->encode($test));
    }
}
