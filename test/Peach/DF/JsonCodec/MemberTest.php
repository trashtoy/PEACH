<?php
class Peach_DF_JsonCodec_MemberTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DF_JsonCodec_Member
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DF_JsonCodec_Member();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * インスタンス化直後はキーと値が null となっていることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_Member::__construct
     * @covers Peach_DF_JsonCodec_Member::getKey
     * @covers Peach_DF_JsonCodec_Member::getValue
     */
    public function test__construct()
    {
        $expr = $this->object;
        $this->assertNull($expr->getKey());
        $this->assertNull($expr->getValue());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Member::handle
     * @covers Peach_DF_JsonCodec_Member::getKey
     * @covers Peach_DF_JsonCodec_Member::getValue
     */
    public function testHandle()
    {
        $context = new Peach_DF_JsonCodec_Context('"hoge" : 135', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
        $this->assertSame("hoge", $expr->getKey());
        $this->assertSame(135, $expr->getValue());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Member::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNoSeparator()
    {
        $context = new Peach_DF_JsonCodec_Context('"hoge"  ', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Member::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNoValue()
    {
        $context = new Peach_DF_JsonCodec_Context('"hoge":   ', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Member::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testHandleFailByNoString()
    {
        $context = new Peach_DF_JsonCodec_Context('hoge : 135 ', new Peach_Util_ArrayMap());
        $expr    = $this->object;
        $expr->handle($context);
    }
}
