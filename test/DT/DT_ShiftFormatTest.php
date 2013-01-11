<?php

require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_ShiftFormat.
 * 
 * このテストでは, システム時刻の時差を UTC+9, フォーマットの時差を UTC-5 と仮定します.
 * システム時刻とフォーマットの時差が 14 時間となるため,
 * 
 * - parse の際は, 変換結果を 14 時間遅らせたオブジェクト
 * - format の際は, 変換結果を 14 時間早めた文字列
 * 
 * となることを確認します.
 */
class DT_ShiftFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DT_ShiftFormat
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->defaultTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Tokyo");
        $f = new DT_SimpleFormat("Y.m.d H:i:s");
        $this->object = new DT_ShiftFormat($f, 300, -540); // Out: Newyork, In: Japan
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
     * 第二引数を省略した場合に, システム時刻の時差が設定されることを確認します.
     */
    public function test__construct()
    {
        $f        = DT_W3cDatetimeFormat::getInstance();
        $test     = new DT_ShiftFormat($f, 300);
        $expected = new DT_ShiftFormat($f, 300, -540);
        $this->assertEquals($expected, $test);
    }
    
    /**
     * parseDate のテストです. オリジナルと同じ結果になることを確認します.
     */
    public function testParseDate()
    {
        $this->assertEquals(new DT_Date(2012, 5, 20), $this->object->parseDate("2012.05.20 17:30:45"));
    }
    
    /**
     * parseDatetime のテストです.
     * 表示時刻の 14 時間後の時間オブジェクトが生成されることを確認します.
     */
    public function testParseDatetime()
    {
        $this->assertEquals(new DT_Datetime(2012, 5, 21, 7, 30), $this->object->parseDatetime("2012.05.20 17:30:45"));
    }

    /**
     * parseTimestamp のテストです. 
     * 表示時刻の 14 時間後の時間オブジェクトが生成されることを確認します.
     */
    public function testParseTimestamp()
    {
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 7, 30, 45), $this->object->parseTimestamp("2012.05.20 17:30:45"));
    }
    
    /**
     * formatDate のテストです.
     * オリジナルと同じ結果を返すことを確認します.
     */
    public function testFormatDate()
    {
        $this->assertSame("2012.05.21 00:00:00", $this->object->formatDate(new DT_Date(2012, 5, 21)));
    }
    
    /**
     * formatDatetime のテストです.
     * 表示時刻の 14 時間前のフォーマットが出力されることを確認します.
     */
    public function testFormatDatetime()
    {
        $this->assertSame("2012.05.20 17:30:00", $this->object->formatDatetime(new DT_Datetime(2012, 5, 21, 7, 30)));
    }
    
    /**
     * formatTimestamp のテストです.
     * 表示時刻の 14 時間前のフォーマットが出力されることを確認します.
     */
    public function testFormatTimestamp()
    {
        $this->assertSame("2012.05.20 17:30:45", $this->object->formatTimestamp(new DT_Timestamp(2012, 5, 21, 7, 30, 45)));
    }
}
?>