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
        $this->object = DT_HttpDateFormat::getInstance();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }
    
    /**
     * 以下を確認します.
     * 
     * - DT_HttpDateFormat オブジェクトを返す
     * - 複数回実行した際に同一のオブジェクトを返す
     */
    public function testGetInstance() {
        $f1 = DT_HttpDateFormat::getInstance();
        $f2 = DT_HttpDateFormat::getInstance();
        $this->assertInstanceOf("DT_HttpDateFormat", $f1);
        $this->assertSame($f1, $f2);
    }
    
    /**
     * どのテスト用フォーマットも 2009-02-13 に変換されることを確認します.
     */
    public function testParseDate() {
        $expected = new DT_Date(2009, 2, 13);
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
     * どのテスト用フォーマットも 2009-02-13T23:31 に変換されることを確認します.
     */
    public function testParseDatetime() {
        $expected = new DT_Datetime(2009, 2, 13, 23, 31);
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
     * どのテスト用フォーマットも 2009-02-13T23:31:30 に変換されることを確認します.
     */
    public function testParseTimestamp() {
        $expected = new DT_Timestamp(2009, 2, 13, 23, 31, 30);
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
     * その日の0時0分の時刻で書式化されることを確認します.
     */
    public function testFormatDate() {
        $d = new DT_Date(2009, 2, 13);
        $this->assertSame("Fri, 13 Feb 2009 00:00 GMT", $this->object->formatDate($d));
    }

    /**
     * その時刻の HTTP-date で書式化されることを確認します.
     */
    public function testFormatDatetime() {
        $d = new DT_Datetime(2009, 2, 13, 23, 31);
        $this->assertSame("Fri, 13 Feb 2009 23:31 GMT", $this->object->formatDatetime($d));
    }

    /**
     * その時刻の HTTP-date で書式化されることを確認します.
     */
    public function testFormatTimestamp() {
        $d = new DT_Timestamp(2009, 2, 13, 23, 31, 30);
        $this->assertSame("Fri, 13 Feb 2009 23:31:30 GMT", $this->object->formatTimestamp($d));
    }
}
?>
