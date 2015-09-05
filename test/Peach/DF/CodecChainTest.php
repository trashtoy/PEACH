<?php
class Peach_DF_CodecChainTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_CodecChain
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $first  = new Peach_DF_JsonCodec();
        $second = Peach_DF_Base64Codec::getInstance();
        $this->object = new Peach_DF_CodecChain($first, $second);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * 引数に CodecChain を指定して 3 種類以上の Codec を連結させた場合に,
     * どのような順番で連結させても等価なオブジェクトが生成されることを確認します.
     * 
     * @covers Peach_DF_CodecChain::__construct
     */
    public function test__construct()
    {
        $j1   = new Peach_DF_JsonCodec(1);
        $j2   = new Peach_DF_JsonCodec(2);
        $j3   = new Peach_DF_JsonCodec(3);
        $j4   = new Peach_DF_JsonCodec(4);
        $j5   = new Peach_DF_JsonCodec(5);
        
        $o1 = new Peach_DF_CodecChain($j4, $j5);
        $o2 = new Peach_DF_CodecChain($j3, $o1);
        $o3 = new Peach_DF_CodecChain($j2, $o2);
        $o4 = new Peach_DF_CodecChain($j1, $o3);
        
        $o5 = new Peach_DF_CodecChain($j1, $j2);
        $o6 = new Peach_DF_CodecChain($o5, $j3);
        $o7 = new Peach_DF_CodecChain($o6, $j4);
        $o8 = new Peach_DF_CodecChain($o7, $j5);
        
        $this->assertEquals($o4, $o8);
    }
    
    /**
     * 2 番目, 1 番目の順で Codec のデコードが行われることを確認します.
     * 
     * @covers Peach_DF_CodecChain::decode
     */
    public function testDecode()
    {
        $obj      = $this->object;
        $test     = "eyJmb28iOjEsImJhciI6MiwiYmF6IjozfQ==";
        $expected = new stdClass();
        $expected->foo = 1;
        $expected->bar = 2;
        $expected->baz = 3;
        
        $this->assertEquals($expected, $obj->decode($test));
    }
    
    /**
     * 1 番目, 2 番目の順で Codec のエンコードが行われることを確認します.
     * 
     * @covers Peach_DF_CodecChain::encode
     */
    public function testEncode()
    {
        $obj      = $this->object;
        $test     = array(
            "foo" => 1,
            "bar" => 2,
            "baz" => 3,
        );
        $expected = "eyJmb28iOjEsImJhciI6MiwiYmF6IjozfQ==";
        $this->assertSame($expected, $obj->encode($test));
    }
    
    /**
     * 元のチェーンの末尾に他の Codec を連結した新しい CodecChain
     * が生成されることを確認します.
     * 
     * @covers Peach_DF_CodecChain::append
     */
    public function testAppend()
    {
        $j1   = new Peach_DF_JsonCodec(1);
        $j2   = new Peach_DF_JsonCodec(2);
        $j3   = new Peach_DF_JsonCodec(3);
        
        $test     = new Peach_DF_CodecChain($j1, $j2);
        $expected = new Peach_DF_CodecChain($j1, new Peach_DF_CodecChain($j2, $j3));
        $this->assertEquals($expected, $test->append($j3));
    }
    
    /**
     * 元のチェーンの先頭に他の Codec を連結させた新しい CodecChain
     * が生成されることを確認します.
     * 
     * @covers Peach_DF_CodecChain::prepend
     */
    public function testPrepend()
    {
        $j1   = new Peach_DF_JsonCodec(1);
        $j2   = new Peach_DF_JsonCodec(2);
        $j3   = new Peach_DF_JsonCodec(3);
        
        $test     = new Peach_DF_CodecChain($j2, $j3);
        $expected = new Peach_DF_CodecChain($j1, $test);
        $this->assertEquals($expected, $test->prepend($j1));
    }
}
