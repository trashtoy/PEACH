<?php
/**
 * Test class for Peach_DT_W3CDatetimeFormat.
 */
class Peach_DT_W3cDatetimeFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * Peach_DT_W3cDatetimeFormat の配列です.
     * @var array
     */
    protected $objects;
    
    /**
     * @var string
     */
    private $defaultTZ;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->objects = array(
            new Peach_DT_W3cDatetimeFormat(-540, -540),
            new Peach_DT_W3cDatetimeFormat(   0, -540),
            new Peach_DT_W3cDatetimeFormat( 330, -540),
            new Peach_DT_W3cDatetimeFormat(-540,    0),
            new Peach_DT_W3cDatetimeFormat(-540,  330),
        );
        $this->defaultTZ = date_default_timezone_get();
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
     * 引数を省略した場合に, Peach_DT_Util::getTimeZoneOffset()
     * と同じ値がセットされることを確認します.
     */
    public function test__construct()
    {
        $this->assertEquals(new Peach_DT_W3cDatetimeFormat(-540, -540), new Peach_DT_W3cDatetimeFormat());
        $this->assertEquals(new Peach_DT_W3cDatetimeFormat(300,  -540), new Peach_DT_W3cDatetimeFormat(300));
    }
    
    /**
     * getInstance() のテストです. 以下を確認します.
     * 
     * - parse 時にタイムゾーン文字列をすべて無視する
     * - format 時にタイムゾーン文字列を付与しない
     * 
     * @covers Peach_DT_W3cDatetimeFormat::getInstance
     */
    public function testGetInstance()
    {
        $obj = Peach_DT_W3cDatetimeFormat::getInstance();
        $this->assertEquals(new Peach_DT_Datetime(2012, 5, 21, 7, 30), $obj->parseDatetime("2012-05-21T07:30+05:00"));
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 45), $obj->parseTimestamp("2012-05-21T07:30:45-06:30"));
        $this->assertSame("2012-05-21T07:30",    $obj->formatDatetime(new Peach_DT_Datetime(2012, 5, 21, 7, 30)));
        $this->assertSame("2012-05-21T07:30:45", $obj->formatTimestamp(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 45)));
    }
    
    /**
     * "YYYY-MM-DD" 形式の文字列を,
     * 対応する Peach_DT_Date オブジェクトに変換することを確認します.
     * 
     * @covers Peach_DT_W3cDatetimeFormat::parseDate
     */
    public function testParseDate()
    {
        $format = "2012-05-21";
        $expect = new Peach_DT_Date(2012, 5, 21);
        foreach ($this->objects as $obj) {
            $this->assertEquals($expect, $obj->parseDate($format));
        }
    }
    
    /**
     * 不正なフォーマットの場合に InvalidArgumentException をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_W3cDatetimeFormat::parseDate
     */
    public function testParseDateFail()
    {
        $obj = $this->objects[0];
        $obj->parseDate("foobar");
    }
    
    /**
     * "YYYY-MM-DD?hh:mm" 形式の文字列を,
     * 対応する Peach_DT_Datetime オブジェクトに変換することを確認します.
     * 
     * @covers Peach_DT_W3cDatetimeFormat::parseDatetime
     */
    public function testParseDatetime()
    {
        $format = array(
            "2012-05-21 07:30",
            "2012-05-20T22:30Z",
            "2012-05-20T17:00-05:30",
        );
        $obj    = $this->objects[0];
        $expect = new Peach_DT_Datetime(2012, 5, 21, 7, 30);
        foreach ($format as $f) {
            $this->assertEquals($expect, $obj->parseDatetime($f));
        }
    }
    
    /**
     * 不正なフォーマットの場合に InvalidArgumentException をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_W3cDatetimeFormat::parseDatetime
     */
    public function testParseDatetimeFail()
    {
        $obj = $this->objects[0];
        $obj->parseDatetime("foobar");
    }
    
    /**
     * "YYYY-MM-DD?hh:mm:ss" 形式の文字列を,
     * 対応する Peach_DT_Timestamp オブジェクトに変換することを確認します
     * 
     * @covers Peach_DT_W3cDatetimeFormat::parseTimestamp
     */
    public function testParseTimestamp()
    {
        $format = array(
            "2012-05-21 07:30:45",
            "2012-05-20T22:30:45Z",
            "2012-05-20T17:00:45-05:30",
        );
        $obj    = $this->objects[0];
        $expect = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 45);
        foreach ($format as $f) {
            $this->assertEquals($expect, $obj->parseTimestamp($f));
        }
    }
    
    /**
     * 不正なフォーマットの場合に InvalidArgumentException をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_W3cDatetimeFormat::parseTimestamp
     */
    public function testParseTimestampFail()
    {
        $obj = $this->objects[0];
        $obj->parseTimestamp("foobar");
    }
    
    /**
     * タイムゾーンの設定に関係なく、常に "YYYY-MM-DD" 形式の文字列を出力することを確認します.
     * @covers Peach_DT_W3cDatetimeFormat::formatDate
     */
    public function testFormatDate()
    {
        $d = new Peach_DT_Date(2012, 5, 21);
        $f = "2012-05-21";
        foreach ($this->objects as $obj) {
            $this->assertSame($f, $obj->formatDate($d));
        }
    }
    
    /**
     * "YYYY-MM-DDThh:mm+09:00" 形式の文字列を生成することを確認します.
     * タイムゾーンの設定がある場合は "YYYY-MM-DDThh:mm+9:00" のような文字列を生成します.
     * @covers Peach_DT_W3cDatetimeFormat::formatDatetime
     */
    public function testFormatDatetime()
    {
        $d = new Peach_DT_Datetime(2012, 5, 21, 7, 30);
        $expected = array(
            "2012-05-21T07:30+09:00",
            "2012-05-20T22:30Z",
            "2012-05-20T17:00-05:30",
            "2012-05-21T16:30+09:00",
            "2012-05-21T22:00+09:00",
        );
        for ($i = 0; $i < 5; $i ++) {
            $obj = $this->objects[$i];
            $exp = $expected[$i];
            $this->assertSame($exp, $obj->formatDatetime($d));
        }
    }
    
    /**
     * "YYYY-MM-DDThh:mm:ss" 形式の文字列を生成することを確認します.
     * タイムゾーンの設定がある場合は "YYYY-MM-DDThh:mm:ss+9:00" のような文字列を生成します.
     * @covers Peach_DT_W3cDatetimeFormat::formatTimestamp
     */
    public function testFormatTimestamp()
    {
        $d = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 45);
        $expected = array(
            "2012-05-21T07:30:45+09:00",
            "2012-05-20T22:30:45Z",
            "2012-05-20T17:00:45-05:30",
            "2012-05-21T16:30:45+09:00",
            "2012-05-21T22:00:45+09:00",
        );
        for ($i = 0; $i < 5; $i ++) {
            $obj = $this->objects[$i];
            $exp = $expected[$i];
            $this->assertSame($exp, $obj->formatTimestamp($d));
        }
    }
}
