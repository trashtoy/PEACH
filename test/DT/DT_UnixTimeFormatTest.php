<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_UnixTimeFormat.
 */
class DT_UnixTimeFormatTest extends PHPUnit_Framework_TestCase {
    private $defaultTZ;
    private $testTime;
    
    /**
     * @var DT_UnixTimeFormat
     */
    protected $object;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->defaultTZ = date_default_timezone_get();
        $this->object    = DT_UnixTimeFormat::getInstance();
        $this->testTime  = "1234567890";
        date_default_timezone_set("Asia/Tokyo");
    }
    
    protected function tearDown() {
        date_default_timezone_set($this->defaultTZ);
    }
    
    /**
     * 以下を確認します.
     * 
     * - DT_UnixTimeFormat オブジェクトを返す
     * - 複数回実行した際に同一のオブジェクトを返す
     */
    public function testGetInstance() {
        $f1 = DT_UnixTimeFormat::getInstance();
        $f2 = DT_UnixTimeFormat::getInstance();
        $this->assertType("DT_UnixTimeFormat", $f1);
        $this->assertSame($f1, $f2);
    }

    /**
     * parseDate の結果が DT_Date になることを確認します.
     */
    public function testParseDate() {
        $p = $this->object->parseDate($this->testTime);
        $this->assertEquals(new DT_Date(2009, 2, 14), $p);
    }

    /**
     * parseDatetime の結果が DT_Datetime になることを確認します.
     */
    public function testParseDatetime() {
        $p = $this->object->parseDatetime($this->testTime);
        $this->assertEquals(new DT_Datetime(2009, 2, 14, 8, 31), $p);
    }

    /**
     * parseTimestamp の結果が DT_Timestamp になることを確認します.
     */
    public function testParseTimestamp() {
        $p = $this->object->parseTimestamp($this->testTime);
        $this->assertEquals(new DT_Timestamp(2009, 2, 14, 8, 31, 30), $p);
    }

    /**
     * その日の 0 時 0 分 0 秒の Unix time に等しくなることを確認します.
     */
    public function testFormatDate() {
        $this->assertSame("1234537200", $this->object->formatDate(new DT_Date(2009, 2, 14)));
    }

    /**
     * その時刻の 0 秒の時の Unix time に等しくなることを確認します.
     */
    public function testFormatDatetime() {
        $this->assertSame("1234567860", $this->object->formatDatetime(new DT_Datetime(2009, 2, 14, 8, 31)));
    }

    /**
     * その時刻の Unix time に等しくなることを確認します.
     */
    public function testFormatTimestamp() {
        $this->assertSame($this->testTime, $this->object->formatTimestamp(new DT_Timestamp(2009, 2, 14, 8, 31, 30)));
    }

}

?>
