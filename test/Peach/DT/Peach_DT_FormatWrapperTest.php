<?php
/**
 * Test class for Peach_DT_FormatWrapper.
 */
class Peach_DT_FormatWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DT_FormatWrapper
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $original     = Peach_DT_W3cDatetimeFormat::getInstance();
        $f            = new Peach_DT_FormatWrapper($original);
        $this->object = $f;
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタの引数と等しい時間オブジェクトを返すことを確認します.
     * @covers Peach_DT_FormatWrapper::getOriginal
     * @covers Peach_DT_FormatWrapper::__construct
     */
    public function testGetOriginal()
    {
        $original = new Peach_DT_SimpleFormat("Y.n.d");
        $f        = new Peach_DT_FormatWrapper($original);
        $this->assertSame($original, $f->getOriginal());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::formatDate
     */
    public function testFormatDate()
    {
        $this->assertSame("2012-05-21", $this->object->formatDate(new Peach_DT_Date(2012, 5, 21)));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::formatDatetime
     */
    public function testFormatDatetime()
    {
        $this->assertSame("2012-05-21T07:30", $this->object->formatDatetime(new Peach_DT_Datetime(2012, 5, 21, 7, 30)));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::formatTimestamp
     */
    public function testFormatTimestamp()
    {
        $this->assertSame("2012-05-21T07:30:15", $this->object->formatTimestamp(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15)));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::parseDate
     */
    public function testParseDate()
    {
        $this->assertEquals(new Peach_DT_Date(2012, 5, 21), $this->object->parseDate("2012-05-21"));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::parseDatetime
     */
    public function testParseDatetime()
    {
        $this->assertEquals(new Peach_DT_Datetime(2012, 5, 21, 7, 30), $this->object->parseDatetime("2012-05-21T07:30"));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_FormatWrapper::parseTimestamp
     */
    public function testParseTimestamp()
    {
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15), $this->object->parseTimestamp("2012-05-21T07:30:15"));
    }
}
