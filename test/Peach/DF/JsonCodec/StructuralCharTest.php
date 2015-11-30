<?php
class Peach_DF_JsonCodec_StructuralCharTest extends PHPUnit_Framework_TestCase
{
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
     * インスタンス化直後は $result が null となっていることを確認します.
     * 
     * @covers Peach_DF_JsonCodec_StructuralChar::__construct
     * @covers Peach_DF_JsonCodec_StructuralChar::getResult
     */
    public function test__construct()
    {
        $expr = new Peach_DF_JsonCodec_StructuralChar(array(",", "}"));
        $this->assertNull($expr->getResult());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_StructuralChar::handle
     * @covers Peach_DF_JsonCodec_StructuralChar::handleChar
     * @covers Peach_DF_JsonCodec_StructuralChar::getResult
     */
    public function testHandleAndGetResult()
    {
        $context = new Peach_DF_JsonCodec_Context("    }    ", new Peach_Util_ArrayMap());
        $expr    = new Peach_DF_JsonCodec_StructuralChar(array(",", "}"));
        $expr->handle($context);
        $this->assertSame("}", $expr->getResult());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_StructuralChar::handle
     * @expectedException Peach_DF_JsonCodec_DecodeException
     * @expectedExceptionMessage Unexpected end of JSON at line 3, column 1
     */
    public function testHandleFailByEndOfJson()
    {
        $context = new Peach_DF_JsonCodec_Context("    \r\n    \n", new Peach_Util_ArrayMap());
        $expr    = new Peach_DF_JsonCodec_StructuralChar(array(",", "}"));
        $expr->handle($context);
    }
    
    /**
     * @covers Peach_DF_JsonCodec_StructuralChar::handle
     * @covers Peach_DF_JsonCodec_StructuralChar::handleChar
     * @expectedException Peach_DF_JsonCodec_DecodeException
     * @expectedExceptionMessage 'x' is not allowed (expected: ',', '}') at line 3, column 4
     */
    public function testHandleFailByInvalidChar()
    {
        $context = new Peach_DF_JsonCodec_Context("    \n        \r   x   \r\n    ", new Peach_Util_ArrayMap());
        $expr    = new Peach_DF_JsonCodec_StructuralChar(array(",", "}"));
        $expr->handle($context);
    }
}
