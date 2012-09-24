<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_W3CDatetimeFormat.
 */
class DT_W3CDatetimeFormatTest extends PHPUnit_Framework_TestCase {

    /**
     * @var DT_W3CDatetimeFormat
     */
    protected $object1;
    protected $object2;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object1 = DT_W3CDatetimeFormat::getDefault();
        $this->object2 = new DT_W3CDatetimeFormat(9);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
    }

    /**
     * 引数なしのコンストラクタで生成したオブジェクトと等しいことを確認します.
     */
    public function testGetDefault() {
        $this->assertEquals(new DT_W3CDatetimeFormat(), DT_W3CDatetimeFormat::getDefault());
    }

    /**
     * 以下を確認します.
     * 
     * - "YYYY-MM-DD" 形式の文字列を, 対応する DT_Date オブジェクトに変換すること
     * - 不正なフォーマットの場合に Exception をスローすること
     */
    public function testParseDate() {
        $format = "2012-05-21";
        $expect = new DT_Date(2012, 5, 21);
        $this->assertEquals($expect, $this->object1->parseDate($format));
        $this->assertEquals($expect, $this->object2->parseDate($format));
        try {
            $this->object1->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * 以下を確認します.
     * 
     * - "YYYY-MM-DD?hh:mm" 形式の文字列を, 対応する DT_Datetime オブジェクトに変換すること
     * - 不正なフォーマットの場合に Exception をスローすること
     */
    public function testParseDatetime() {
        $format = array(
            "2012-05-21 07:30",
            "2012-05-21T07:30",
            "2012-05-21T07:30abcdXYZ",
        );
        $expect = new DT_Datetime(2012, 5, 21, 7, 30);
        foreach ($format as $f) {
            $this->assertEquals($expect, $this->object1->parseDatetime($f));
            $this->assertEquals($expect, $this->object2->parseDatetime($f));          
        }
        try {
            $this->object1->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * 以下を確認します.
     * 
     * - "YYYY-MM-DD?hh:mm:ss" 形式の文字列を, 対応する DT_Timestamp オブジェクトに変換すること
     * - 不正なフォーマットの場合に Exception をスローすること
     */
    public function testParseTimestamp() {
        $format = array(
            "2012-05-21 07:30:15",
            "2012-05-21T07:30:15",
            "2012-05-21T07:30:15abcdXYZ",
        );
        $expect = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        foreach ($format as $f) {
            $this->assertEquals($expect, $this->object1->parseTimestamp($f));
            $this->assertEquals($expect, $this->object2->parseTimestamp($f));          
        }
        try {
            $this->object1->parseDate("foobar");
            $this->fail();
        }
        catch (Exception $e) {}
    }

    /**
     * タイムゾーンの設定に関係なく、常に "YYYY-MM-DD" 形式の文字列を出力することを確認します.
     */
    public function testFormatDate() {
        $d = new DT_Date(2012, 5, 21);
        $f = "2012-05-21";
        $this->assertSame($f, $this->object1->formatDate($d));
        $this->assertSame($f, $this->object2->formatDate($d));
    }

    /**
     * "YYYY-MM-DDThh:mm" 形式の文字列を生成することを確認します.
     * タイムゾーンの設定がある場合は "YYYY-MM-DDThh:mm+9:00" のような文字列を生成します.
     */
    public function testFormatDatetime() {
        $d = new DT_Datetime(2012, 5, 21, 7, 30);
        $this->assertSame("2012-05-21T07:30",       $this->object1->formatDatetime($d));
        $this->assertSame("2012-05-21T07:30+09:00", $this->object2->formatDatetime($d));
    }

    /**
     * "YYYY-MM-DDThh:mm:ss" 形式の文字列を生成することを確認します.
     * タイムゾーンの設定がある場合は "YYYY-MM-DDThh:mm:ss+9:00" のような文字列を生成します.
     */
    public function testFormatTimestamp() {
        $d = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertSame("2012-05-21T07:30:15",       $this->object1->formatDatetime($d));
        $this->assertSame("2012-05-21T07:30:15+09:00", $this->object2->formatDatetime($d));
    }
}
?>
