<?php
/**
 * Test class for Peach_DT_UnixTimeFormat.
 */
class Peach_DT_UnixTimeFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $defaultTZ;
    
    /**
     * @var int
     */
    private $testTime;
    
    /**
     * @var Peach_DT_UnixTimeFormat
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->defaultTZ = date_default_timezone_get();
        $this->object    = Peach_DT_UnixTimeFormat::getInstance();
        $this->testTime  = "1234567890";
        date_default_timezone_set("Asia/Tokyo");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        date_default_timezone_set($this->defaultTZ);
    }
    
    /**
     * getInstance() のテストです. 以下を確認します.
     * 
     * - Peach_DT_UnixTimeFormat オブジェクトを返す
     * - 複数回実行した際に同一のオブジェクトを返す
     * 
     * @covers Peach_DT_UnixTimeFormat::getInstance
     */
    public function testGetInstance()
    {
        $f1 = Peach_DT_UnixTimeFormat::getInstance();
        $f2 = Peach_DT_UnixTimeFormat::getInstance();
        $this->assertInstanceOf("Peach_DT_UnixTimeFormat", $f1);
        $this->assertSame($f1, $f2);
    }
    
    /**
     * parseDate の結果が Peach_DT_Date になることを確認します.
     * @covers Peach_DT_UnixTimeFormat::parseDate
     */
    public function testParseDate()
    {
        $p = $this->object->parseDate($this->testTime);
        $this->assertEquals(new Peach_DT_Date(2009, 2, 14), $p);
    }
    
    /**
     * parseDatetime の結果が Peach_DT_Datetime になることを確認します.
     * @covers Peach_DT_UnixTimeFormat::parseDatetime
     */
    public function testParseDatetime()
    {
        $p = $this->object->parseDatetime($this->testTime);
        $this->assertEquals(new Peach_DT_Datetime(2009, 2, 14, 8, 31), $p);
    }
    
    /**
     * parseTimestamp の結果が Peach_DT_Timestamp になることを確認します.
     * @covers Peach_DT_UnixTimeFormat::parseTimestamp
     */
    public function testParseTimestamp()
    {
        $p = $this->object->parseTimestamp($this->testTime);
        $this->assertEquals(new Peach_DT_Timestamp(2009, 2, 14, 8, 31, 30), $p);
    }
    
    /**
     * その日の 0 時 0 分 0 秒の Unix time に等しくなることを確認します.
     * @covers Peach_DT_UnixTimeFormat::formatDate
     */
    public function testFormatDate()
    {
        $this->assertSame("1234537200", $this->object->formatDate(new Peach_DT_Date(2009, 2, 14)));
    }
    
    /**
     * その時刻の 0 秒の時の Unix time に等しくなることを確認します.
     * @covers Peach_DT_UnixTimeFormat::formatDatetime
     */
    public function testFormatDatetime()
    {
        $this->assertSame("1234567860", $this->object->formatDatetime(new Peach_DT_Datetime(2009, 2, 14, 8, 31)));
    }
    
    /**
     * その時刻の Unix time に等しくなることを確認します.
     * @covers Peach_DT_UnixTimeFormat::formatTimestamp
     */
    public function testFormatTimestamp()
    {
        $this->assertSame($this->testTime, $this->object->formatTimestamp(new Peach_DT_Timestamp(2009, 2, 14, 8, 31, 30)));
    }
}
