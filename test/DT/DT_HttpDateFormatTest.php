<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_HttpDateFormat.
 */
class DT_HttpDateFormatTest extends PHPUnit_Framework_TestCase {

    /**
     * @var DT_HttpDateFormat
     */
    protected $object;
    
    private $inputFormat = array(
        "Fri, 13 Feb 2009 23:31:30 GMT",
        "Friday, 13-Feb-09 23:31:30 GMT",
        "Fri Feb 13 23:31:30 2009",
    );
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new DT_HttpDateFormat(-540);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }
    
    /**
     * コンストラクタのテストです. 
     * 引数を省略した場合, DT_Util::getTimeZoneOffset()
     * を引数にするのと同じ結果になることを確認します.
     */
    public function test__construct() {
        $offset = DT_Util::getTimeZoneOffset();
        $this->assertEquals(new DT_HttpDateFormat($offset), new DT_HttpDateFormat());
    }
    
    /**
     * どのテスト用フォーマットも 2009-02-14 に変換されることを確認します.
     */
    public function testParseDate() {
        $expected = new DT_Date(2009, 2, 14);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseDate($f));
        }
        try {
            $this->object->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * どのテスト用フォーマットも 2009-02-14T08:31 に変換されることを確認します.
     */
    public function testParseDatetime() {
        $expected = new DT_Datetime(2009, 2, 14, 8, 31);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseDatetime($f));
        }
        try {
            $this->object->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * どのテスト用フォーマットも 2009-02-14T08:31:30 に変換されることを確認します.
     */
    public function testParseTimestamp() {
        $expected = new DT_Timestamp(2009, 2, 14, 8, 31, 30);
        foreach ($this->inputFormat as $f) {
            $this->assertEquals($expected, $this->object->parseTimestamp($f));
        }
        try {
            $this->object->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * その日の0時0分の時刻を GMT に変換した結果を返します.
     */
    public function testFormatDate() {
        $d = new DT_Date(2009, 2, 14);
        $this->assertSame("Fri, 13 Feb 2009 15:00 GMT", $this->object->formatDate($d));
    }

    /**
     * その時刻の HTTP-date で書式化されることを確認します.
     */
    public function testFormatDatetime() {
        $d = new DT_Datetime(2009, 2, 14, 8, 31);
        $this->assertSame("Fri, 13 Feb 2009 23:31 GMT", $this->object->formatDatetime($d));
    }

    /**
     * その時刻の HTTP-date で書式化されることを確認します.
     */
    public function testFormatTimestamp() {
        $d = new DT_Timestamp(2009, 2, 14, 8, 31, 30);
        $this->assertSame("Fri, 13 Feb 2009 23:31:30 GMT", $this->object->formatTimestamp($d));
    }
}
?>
