<?php
class Peach_DF_JsonCodec_ContextTest extends PHPUnit_Framework_TestCase
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
     * @covers Peach_DF_JsonCodec_Context::__construct
     * @covers Peach_DF_JsonCodec_Context::getOption
     */
    public function testGetOption()
    {
        $options = new Peach_Util_ArrayMap();
        $options->put(Peach_DF_JsonCodec::BIGINT_AS_STRING, true);
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", $options);
        $this->assertFalse($context->getOption(Peach_DF_JsonCodec::OBJECT_AS_ARRAY));
        $this->assertTrue($context->getOption(Peach_DF_JsonCodec::BIGINT_AS_STRING));
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::__construct
     * @covers Peach_DF_JsonCodec_Context::hasNext
     */
    public function testHasNext()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        
        // read "This "
        for ($i = 0; $i < 5; $i++) {
            $context->next();
        }
        $this->assertTrue($context->hasNext());
        
        // read "is a pen."
        for ($i = 0; $i < 9; $i++) {
            $context->next();
        }
        $this->assertFalse($context->hasNext());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::encodeCodepoint
     */
    public function testEncodeCodepoint()
    {
        $context  = new Peach_DF_JsonCodec_Context("", new Peach_Util_ArrayMap());
        $chr      = chr(227) . chr(129) . chr(130); // "あ";
        $this->assertSame($chr, $context->encodeCodepoint(0x3042));
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::__construct
     * @covers Peach_DF_JsonCodec_Context::current
     * @covers Peach_DF_JsonCodec_Context::computeCurrent
     * @covers Peach_DF_JsonCodec_Context::next
     */
    public function testCurrentAndNext()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        
        // read "This "
        for ($i = 0; $i < 5; $i++) {
            $context->next();
        }
        $this->assertSame("i", $context->current());
        
        // read "is a pen."
        for ($i = 0; $i < 9; $i++) {
            $context->next();
        }
        $this->assertNull($context->current());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::currentCodePoint
     */
    public function testCurrentCodePoint()
    {
        $context = new Peach_DF_JsonCodec_Context("Test", new Peach_Util_ArrayMap());
        $this->assertSame(0x54, $context->currentCodePoint());
        $context->next();
        $this->assertSame(0x65, $context->currentCodePoint());
        $context->next();
        $this->assertSame(0x73, $context->currentCodePoint());
        $context->next();
        $this->assertSame(0x74, $context->currentCodePoint());
        $context->next();
        $this->assertNull($context->currentCodePoint());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::current
     * @covers Peach_DF_JsonCodec_Context::computeCurrent
     * @covers Peach_DF_JsonCodec_Context::next
     */
    public function testCurrentWithBreakcode()
    {
        $context = new Peach_DF_JsonCodec_Context("\n\n\r\r\r\n\r\n", new Peach_Util_ArrayMap());
        $this->assertSame("\n", $context->current());
        $this->assertSame("\n", $context->next());
        $this->assertSame("\r", $context->next());
        $this->assertSame("\r", $context->next());
        $this->assertSame("\r\n", $context->next());
        $this->assertSame("\r\n", $context->next());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::throwException
     */
    public function testThrowException()
    {
        $context = new Peach_DF_JsonCodec_Context("This\nis\r\na pen.", new Peach_Util_ArrayMap());
        
        // read "This is a "
        for ($i = 0; $i < 10; $i++) {
            $context->next();
        }
        try {
            $context->throwException("Test");
            $this->fail();
        } catch (Peach_DF_JsonCodec_DecodeException $e) {
            $this->assertSame("Test at line 3, column 3", $e->getMessage());
        }
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::next
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testNextFail()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        for ($i = 0; $i < 14; $i++) {
            $context->next();
        }
        $this->assertFalse($context->hasNext());
        $context->next();
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::getSequence
     * @covers Peach_DF_JsonCodec_Context::encodeIndex
     */
    public function testGetSequence()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        
        // read "this "
        $context->skip(5);
        $this->assertSame("is a", $context->getSequence(4));
        
        // read "is a "
        $context->skip(5);
        $this->assertSame("pen.", $context->getSequence(10));
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::skip
     */
    public function testSkip()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        
        // read "This "
        $context->skip(5);
        $this->assertSame("i", $context->current());
    }
    
    /**
     * @covers Peach_DF_JsonCodec_Context::skip
     * @expectedException Peach_DF_JsonCodec_DecodeException
     */
    public function testSkipFail()
    {
        $context = new Peach_DF_JsonCodec_Context("This is a pen.", new Peach_Util_ArrayMap());
        
        // read "This is a "
        $context->skip(10);
        
        // The remaining count is 4, but skipping 5
        $context->skip(5);
    }
}
