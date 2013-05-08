<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_FormatWrapper.
 */
class DT_FormatWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DT_FormatWrapper
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $original     = DT_W3cDatetimeFormat::getInstance();
        $f            = new DT_FormatWrapper($original);
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
     */
    public function testGetOriginal()
    {
        $original = new DT_SimpleFormat("Y.n.d");
        $f        = new DT_FormatWrapper($original);
        $this->assertSame($original, $f->getOriginal());
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testFormatDate()
    {
        $this->assertSame("2012-05-21", $this->object->formatDate(new DT_Date(2012, 5, 21)));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testFormatDatetime()
    {
        $this->assertSame("2012-05-21T07:30", $this->object->formatDatetime(new DT_Datetime(2012, 5, 21, 7, 30)));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testFormatTimestamp()
    {
        $this->assertSame("2012-05-21T07:30:15", $this->object->formatTimestamp(new DT_Timestamp(2012, 5, 21, 7, 30, 15)));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testParseDate()
    {
        $this->assertEquals(new DT_Date(2012, 5, 21), $this->object->parseDate("2012-05-21"));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testParseDatetime()
    {
        $this->assertEquals(new DT_Datetime(2012, 5, 21, 7, 30), $this->object->parseDatetime("2012-05-21T07:30"));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testParseTimestamp()
    {
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 7, 30, 15), $this->object->parseTimestamp("2012-05-21T07:30:15"));
    }
}
?>
