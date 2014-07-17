<?php
/**
 * Test class for Peach_DT_SimpleFormat.
 */
class Peach_DT_SimpleFormatTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var array
     */
    private $testFormats;
    
    /**
     *
     * @var array
     */
    private $sampleList;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        if ($this->testFormats === null) {
            $this->testFormats = $this->createObject(array(
                array("YmdHis", "Y年n月j日G時f分b秒", "\\Y=Y \\n=n \\j=j \\H=H \\i=i \\s=s"),
                array("Y/m/d",  "Y.n.j"),
                array("H:i:s",  "G時f分b秒")
            ));
        }
        if ($this->sampleList === null) {
            $d1 = new Peach_DT_Date(2012, 5, 21);
            $d2 = new Peach_DT_Datetime(2012, 5, 21, 7, 30);
            $d3 = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 9);
            $d4 = new Peach_DT_Date(2012, 3, 7);
            $d5 = new Peach_DT_Datetime(2012, 3, 7, 0, 0);
            $d6 = new Peach_DT_Timestamp(2012, 3, 7, 0, 0, 0);
            $d7 = Peach_DT_Date::now();
            $d8 = Peach_DT_Datetime::now()->setAll(array("hour" => 8, "minute" => 6));
            $d9 = Peach_DT_Timestamp::now()->setAll(array("hour" => 8, "minute" => 6, "second" => 4));
            $this->sampleList = array(
                new Peach_DT_SimpleFormatTest_Sample(
                    "YmdHis",
                    new Peach_DT_SimpleFormatTest_ParseData("20120521073009",                 "2012052112345",                $d1, $d2, $d3)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "Y年n月j日G時f分b秒",
                    new Peach_DT_SimpleFormatTest_ParseData("2012年5月21日7時30分9秒",        "2012年05月21日07時30分09秒",   $d1, $d2, $d3)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "\\Y=Y \\n=n \\j=j \\H=H \\i=i \\s=s",
                    new Peach_DT_SimpleFormatTest_ParseData("Y=2012 n=5 j=21 H=07 i=30 s=09", "Y=2012 n=5 j=21 H=7 i=30 s=9", $d1, $d2, $d3)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "Y/m/d",
                    new Peach_DT_SimpleFormatTest_ParseData("2012/03/07", "2012-03-07", $d4, $d5, $d6)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "Y.n.j",
                    new Peach_DT_SimpleFormatTest_ParseData("2012.3.7",   "hogehoge",   $d4, $d5, $d6)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "H:i:s",
                    new Peach_DT_SimpleFormatTest_ParseData("08:06:04",   "8:06:04",    $d7, $d8, $d9)
                ),
                new Peach_DT_SimpleFormatTest_Sample(
                    "G時f分b秒",
                    new Peach_DT_SimpleFormatTest_ParseData("8時6分4秒",  "8じ6分4秒",  $d7, $d8, $d9)
                ),
            );
        }
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    public static function createObject($arg)
    {
        if (is_array($arg)) {
            return array_map("Peach_DT_SimpleFormatTest::createObject", $arg);
        } else {
            return new Peach_DT_SimpleFormat($arg);
        }
    }
    
    /**
     * コンストラクタの引数に指定した値を返すことを確認します.
     * @covers Peach_DT_SimpleFormat::getFormat
     */
    public function testGetFormat()
    {
        $arg = "\\Y=Y \\n=n \\j=j \\H=H \\i=i \\s=s";
        $f = new Peach_DT_SimpleFormat($arg);
        $this->assertSame($arg, $f->getFormat());
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::parseDate
     */
    public function testParseDate()
    {
        foreach ($this->sampleList as $sample) {
            $object   = $sample->getFormat();
            $valid    = $sample->getValidText();
            $invalid  = $sample->getInvalidText();
            $expected = $sample->getExpected("parseDate");
            $this->assertEquals($expected, $object->parseDate($valid));
            try {
                $object->parseDate($invalid);
                $this->fail();
            } catch (Exception $e) {}
        }
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::parseDatetime
     */
    public function testParseDatetime()
    {
        foreach ($this->sampleList as $sample) {
            $object   = $sample->getFormat();
            $valid    = $sample->getValidText();
            $invalid  = $sample->getInvalidText();
            $expected = $sample->getExpected("parseDatetime");
            $this->assertEquals($expected, $object->parseDatetime($valid));
            try {
                $object->parseDatetime($invalid);
                $this->fail();
            } catch (Exception $e) {}
        }
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::parseTimestamp
     */
    public function testParseTimestamp()
    {
        foreach ($this->sampleList as $sample) {
            $object   = $sample->getFormat();
            $valid    = $sample->getValidText();
            $invalid  = $sample->getInvalidText();
            $expected = $sample->getExpected("parseTimestamp");
            $this->assertEquals($expected, $object->parseTimestamp($valid));
            try {
                $formats[0]->parseTimestamp($invalid);
                $this->fail();
            } catch (Exception $e) {}
        }
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::formatDate
     */
    public function testFormatDate()
    {
        $d    = new Peach_DT_Date(2012, 5, 21);
        $obj  = $this->testFormats;
        $test = array(
            array("20120521000000", "2012年5月21日0時0分0秒", "Y=2012 n=5 j=21 H=00 i=00 s=00"),
            array("2012/05/21",  "2012.5.21"),
            array("00:00:00",  "0時0分0秒")
        );
        for ($i = 0; $i < 3; $i ++) {
            for ($j = 0, $len = count($test[$i]); $j < $len; $j ++) {
                $this->assertSame($test[$i][$j], $obj[$i][$j]->formatDate($d));
            }
        }
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::formatDatetime
     */
    public function testFormatDatetime()
    {
        $d    = new Peach_DT_Datetime(2012, 5, 21, 7, 30);
        $obj  = $this->testFormats;
        $test = array(
            array("20120521073000", "2012年5月21日7時30分0秒", "Y=2012 n=5 j=21 H=07 i=30 s=00"),
            array("2012/05/21",  "2012.5.21"),
            array("07:30:00",  "7時30分0秒")
        );
        for ($i = 0; $i < 3; $i ++) {
            for ($j = 0, $len = count($test[$i]); $j < $len; $j ++) {
                $this->assertSame($test[$i][$j], $obj[$i][$j]->formatDatetime($d));
            }
        }
    }
    
    /**
     * @covers Peach_DT_SimpleFormat::formatTimestamp
     */
    public function testFormatTimestamp()
    {
        $d    = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 9);
        $obj  = $this->testFormats;
        $test = array(
            array("20120521073009", "2012年5月21日7時30分9秒", "Y=2012 n=5 j=21 H=07 i=30 s=09"),
            array("2012/05/21",  "2012.5.21"),
            array("07:30:09",  "7時30分9秒")
        );
        for ($i = 0; $i < 3; $i ++) {
            for ($j = 0, $len = count($test[$i]); $j < $len; $j ++) {
                $this->assertSame($test[$i][$j], $obj[$i][$j]->formatTimestamp($d));
            }
        }
    }
}

/**
 * テストデータをあらわすクラスです
 */
class Peach_DT_SimpleFormatTest_Sample
{
    /**
     * @var Peach_DT_SimpleFormat
     */
    private $format;
    
    /**
     * @var Peach_DT_SimpleFormatTest_ParseData
     */
    private $parseData;
    
    /**
     * @param string $format
     * @param Peach_DT_SimpleFormatTest_ParseData $data
     */
    public function __construct($format, Peach_DT_SimpleFormatTest_ParseData $data)
    {
        $this->format    = new Peach_DT_SimpleFormat($format);
        $this->parseData = $data;
    }
    
    /**
     * テスト対象の SimpleFormat です
     * @return Peach_DT_SimpleFormat
     */
    public function getFormat()
    {
        return $this->format;
    }
    
    /**
     * 妥当なフォーマットです
     * @return string
     */
    public function getValidText()
    {
        return $this->parseData->getValidText();
    }
    
    /**
     * 例外をスローするフォーマットです
     * @return string
     */
    public function getInvalidText()
    {
        return $this->parseData->getInvalidText();
    }
    
    /**
     * @param  string $type
     * @return Peach_DT_Time
     */
    public function getExpected($type)
    {
        return $this->parseData->getExpected($type);
    }
}

class Peach_DT_SimpleFormatTest_ParseData
{
    /**
     * @var string
     */
    private $validText;
    
    /**
     * @var string
     */
    private $invalidText;
    
    /**
     * @var array
     */
    private $expected;
    
    /**
     * @param string        $validText
     * @param string        $invalidText
     * @param Peach_DT_Date $date
     */
    public function __construct($validText, $invalidText, Peach_DT_Date $date, Peach_DT_Datetime $datetime, Peach_DT_Timestamp $timestamp)
    {
        $this->validText   = $validText;
        $this->invalidText = $invalidText;
        $this->expected    = array(
            "parseDate"      => $date,
            "parseDatetime"  => $datetime,
            "parseTimestamp" => $timestamp,
        );
    }
    
    /**
     * 妥当なフォーマットです
     * @return string
     */
    public function getValidText()
    {
        return $this->validText;
    }
    
    /**
     * 例外をスローするフォーマットです
     * @return string
     */
    public function getInvalidText()
    {
        return $this->invalidText;
    }
    
    /**
     * @param  string $type
     * @return Peach_DT_Time
     */
    public function getExpected($type)
    {
        return $this->expected[$type];
    }
}
