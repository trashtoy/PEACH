<?php

require_once 'DT/load.php';

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
        }
        catch (Exception $e) {}
    }

    /**
     * @todo Implement testFormatDate().
     */
    public function testFormatDate() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testFormatDatetime().
     */
    public function testFormatDatetime() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testFormatTimestamp().
     */
    public function testFormatTimestamp() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>
