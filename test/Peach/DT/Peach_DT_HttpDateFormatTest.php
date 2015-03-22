<?php
/**
 * Test class for Peach_DT_HttpDateFormat.
 */
class Peach_DT_HttpDateFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_DT_HttpDateFormat
     */
    protected $object;
    
    /**
     * @var array
     */
    private $inputFormat = array(
        "Fri, 13 Feb 2009 23:31:30 GMT",
        "Friday, 13-Feb-09 23:31:30 GMT",
        "Fri Feb 13 23:31:30 2009",
    );
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Peach_DT_HttpDateFormat(-540);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * コンストラクタのテストです. 
     * 引数を省略した場合, Peach_DT_Util::getTimeZoneOffset()
     * を引数にするのと同じ結果になることを確認します.
     */
    public function test__construct()
    {
        $offset = Peach_DT_Util::getTimeZoneOffset();
        $this->assertEquals(new Peach_DT_HttpDateFormat($offset), new Peach_DT_HttpDateFormat());
    }
    
    /**
     * getInstance のテストです. 以下を確認します.
     * 
     * - 引数なしのコンストラクタと同じ結果を返すこと
     * - 複数回実行した場合に同じインスタンスを返すこと
     * - 引数 $clearCache を指定した場合に新しいインスタンスを生成すること
     * 
     * @covers Peach_DT_HttpDateFormat::getInstance
     */
    public function testGetInstance()
    {
        $f1 = new Peach_DT_HttpDateFormat();
        $f2 = Peach_DT_HttpDateFormat::getInstance();
        $f3 = Peach_DT_HttpDateFormat::getInstance();
        $f4 = Peach_DT_HttpDateFormat::getInstance(true);
        $f5 = Peach_DT_HttpDateFormat::getInstance();
        
        $this->assertEquals($f1, $f2);
        $this->assertSame($f2, $f3);
        $this->assertSame($f4, $f5);
        $this->assertNotSame($f3, $f4);
    }
    
    /**
     * どのテスト用フォーマットも 2009-02-14 をあらわす
     * Date オブジェクトに変換されることを確認します.
     * 
     * @covers Peach_DT_HttpDateFormat::parseDate
     */
    public function testParseDate()
    {
        $expected = new Peach_DT_Date(2009, 2, 14);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseDate($f));
        }
    }
    
    /**
     * 不正なフォーマットを parse した場合に InvalidArgumentException
     * をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_HttpDateFormat::parseDate
     * @covers Peach_DT_HttpDateFormat::throwFormatException
     */
    public function testParseDateFail()
    {
        $this->object->parseDate("foobar");
    }
    
    /**
     * どのテスト用フォーマットも 2009-02-14T08:31 をあらわす
     * Datetime オブジェクトに変換されることを確認します.
     * 
     * @covers Peach_DT_HttpDateFormat::parseDatetime
     * @covers Peach_DT_HttpDateFormat::parseMonthDescription
     * @covers Peach_DT_HttpDateFormat::getFullYear
     */
    public function testParseDatetime()
    {
        $expected = new Peach_DT_Datetime(2009, 2, 14, 8, 31);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseDatetime($f));
        }
    }
    
    /**
     * 不正なフォーマットを parse した場合に InvalidArgumentException
     * をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_HttpDateFormat::parseDatetime
     * @covers Peach_DT_HttpDateFormat::throwFormatException
     */
    public function testParseDatetimeFail()
    {
        $this->object->parseDatetime("foobar");
    }
    
    /**
     * どのテスト用フォーマットも 2009-02-14T08:31:30 をあらわす
     * Timestamp に変換されることを確認します.
     * 
     * @covers Peach_DT_HttpDateFormat::parseTimestamp
     * @covers Peach_DT_HttpDateFormat::parseMonthDescription
     * @covers Peach_DT_HttpDateFormat::getFullYear
     */
    public function testParseTimestamp()
    {
        $expected = new Peach_DT_Timestamp(2009, 2, 14, 8, 31, 30);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseTimestamp($f));
        }
    }
    
    /**
     * 不正なフォーマットを parse した場合に InvalidArgumentException
     * をスローすることを確認します.
     * 
     * @expectedException InvalidArgumentException
     * @covers Peach_DT_HttpDateFormat::parseTimestamp
     * @covers Peach_DT_HttpDateFormat::throwFormatException
     */
    public function testParseTimestampFail()
    {
        $this->object->parseTimestamp("foobar");
    }
    
    /**
     * その日の0時0分の時刻を GMT に変換した結果を返します.
     * @covers Peach_DT_HttpDateFormat::formatDate
     * @covers Peach_DT_HttpDateFormat::getMonthDescription
     * @covers Peach_DT_HttpDateFormat::getDayDescription
     */
    public function testFormatDate()
    {
        $d = new Peach_DT_Date(2009, 2, 14);
        $this->assertSame("Fri, 13 Feb 2009 15:00 GMT", $this->object->formatDate($d));
    }
    
    /**
     * その時刻が HTTP-date で書式化されることを確認します.
     * @covers Peach_DT_HttpDateFormat::formatDatetime
     * @covers Peach_DT_HttpDateFormat::getMonthDescription
     * @covers Peach_DT_HttpDateFormat::getDayDescription
     */
    public function testFormatDatetime()
    {
        $d = new Peach_DT_Datetime(2009, 2, 14, 8, 31);
        $this->assertSame("Fri, 13 Feb 2009 23:31 GMT", $this->object->formatDatetime($d));
    }
    
    /**
     * その時刻が HTTP-date で書式化されることを確認します.
     * @covers Peach_DT_HttpDateFormat::formatTimestamp
     * @covers Peach_DT_HttpDateFormat::getMonthDescription
     * @covers Peach_DT_HttpDateFormat::getDayDescription
     */
    public function testFormatTimestamp()
    {
        $d = new Peach_DT_Timestamp(2009, 2, 14, 8, 31, 30);
        $this->assertSame("Fri, 13 Feb 2009 23:31:30 GMT", $this->object->formatTimestamp($d));
    }
}
