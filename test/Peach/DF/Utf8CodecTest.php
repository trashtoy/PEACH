<?php

class Peach_DF_Utf8CodecTest extends PHPUnit_Framework_TestCase
{
    /**
     * 文字列 "テスト" です.
     * @var string
     */
    private static $test;
    
    /**
     * BOM 文字列 (0xEF, 0xBB, 0xBF) です.
     * @var string
     */
    private static $bom;
    
    /**
     * "テスト" の Unicode 番号 (0x30c6, 0x30b9, 0x30c8) です.
     * @var array
     */
    private static $unicodeList;
    
    public static function setUpBeforeClass()
    {
        self::$test        = implode("", array_map("chr", array(0xE3, 0x83, 0x86, 0xE3, 0x82, 0xB9, 0xE3, 0x83, 0x88))); // "テスト"
        self::$bom         = chr(0xEF) . chr(0xBB) . chr(0xBF);
        self::$unicodeList = array(0x30c6, 0x30b9, 0x30c8);
    }
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }
    
    /**
     * decode() のテストです. 以下について確認します.
     * 
     * - UTF-8 文字列から Unicode 番号の配列への変換が出来ること
     * - 文字列の先頭に BOM が付与されていた場合, それを取り除いた状態で扱うこと
     * - 文字列の末尾に不正なバイト列が存在していた場合は無視すること
     * 
     * @covers Peach_DF_Utf8Codec::decode
     */
    public function testDecode()
    {
        $obj      = new Peach_DF_Utf8Codec();
        $test     = self::$test;
        $expected = self::$unicodeList;
        $this->assertSame($expected, $obj->decode($test));
        $this->assertSame($expected, $obj->decode(self::$bom . $test));
        $this->assertSame($expected, $obj->decode($test . chr(0x90)));
    }

    /**
     * Unicode 番号の配列が UTF-8 の文字列に変換されることを確認します.
     * 
     * @covers Peach_DF_Utf8Codec::encode
     * @covers Peach_DF_Utf8Codec::encodeUnicode
     * @covers Peach_DF_Utf8Codec::appendChar
     * @covers Peach_DF_Utf8Codec::getCharCount
     * @covers Peach_DF_Utf8Codec::getFirstCharPrefix
     */
    public function testEncode()
    {
        $obj      = new Peach_DF_Utf8Codec();
        $test     = self::$unicodeList;
        $expected = self::$test;
        $this->assertSame($expected, $obj->encode($test));
        
        $first     = $test[0];
        $expected2 = substr($expected, 0, 3);
        $this->assertSame($expected2, $obj->encode($first));
        $this->assertSame($expected2, $obj->encode(strval($first)));
        $this->assertSame($expected2, $obj->encode($first + 0x400000));
        
        $chr = ord("T");
        $this->assertSame("T", $obj->encode($chr));
    }
}
