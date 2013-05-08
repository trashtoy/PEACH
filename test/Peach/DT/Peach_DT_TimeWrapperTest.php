<?php
require_once(__DIR__ . "/Peach_DT_AbstractTimeTest.php");

/**
 * Test class for Peach_DT_TimeWrapper.
 */
class Peach_DT_TimeWrapperTest extends Peach_DT_AbstractTimeTest
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
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
     * @covers Peach_DT_TimeWrapper::getOriginal
     */
    public function testGetOriginal()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15), $d->getOriginal());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::getType
     */
    public function testGetType()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(Peach_DT_Time::TYPE_TIMESTAMP, $d->getType());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::before
     */
    public function testBefore()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertTrue($d->before(new Peach_DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertFalse($d->before(new Peach_DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::after
     */
    public function testAfter()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertFalse($d->after(new Peach_DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertTrue($d->after(new Peach_DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::compareTo
     */
    public function testCompareTo()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertLessThan(0, $d->compareTo(new Peach_DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertGreaterThan(0, $d->compareTo(new Peach_DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }
    
    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     * 
     * @covers Peach_DT_TimeWrapper::add
     */
    public function testAdd()
    {
        $d1 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->add("hour", 18);
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 22, 1, 30, 15), $d2->toTimestamp());
        $this->assertInstanceOf("Peach_DT_TimeWrapper", $d2);
    }
    
    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     * 
     * @covers Peach_DT_TimeWrapper::set
     */
    public function testSet()
    {
        $d1 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->set("min", 80);
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 21, 8, 20, 15), $d2->toTimestamp());
        $this->assertInstanceOf("Peach_DT_TimeWrapper", $d2);
    }
    
    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     * 
     * @covers Peach_DT_TimeWrapper::setAll
     */
    public function testSetAll()
    {
        $d1 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->setAll(array("year" => 2013, "month" => -1, "date" => 32, "sec" => 87));
        $this->assertEquals(new Peach_DT_Timestamp(2012, 12, 2, 7, 31, 27), $d2->toTimestamp());
        $this->assertInstanceOf("Peach_DT_TimeWrapper", $d2);
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::get
     */
    public function testGet()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(21, $d->get("date"));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::format
     */
    public function testFormat()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("2012-05-21 07:30:15", $d->format());
        $this->assertSame("2012-05-21T07:30:15", $d->format(Peach_DT_W3cDatetimeFormat::getInstance()));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::formatTime
     */
    public function testFormatTime()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("07:30:15", $d->formatTime());
    }
    
    /**
     * 以下を確認します.
     * 
     * - クラスが異なる場合は FALSE
     * - クラスが同じで, フィールドの比較結果が 0 の場合は TRUE
     * 
     * @covers Peach_DT_TimeWrapper::equals
     */
    public function testEquals()
    {
        $d1 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $d3 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 1, 1, 0, 0, 0));
        $d4 = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertFalse($d1->equals($d2));
        $this->assertFalse($d1->equals($d3));
        $this->assertTrue($d1->equals($d4));
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::getDateCount
     */
    public function testGetDateCount()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(31, $d->getDateCount());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::getDay
     */
    public function testGetDay()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(1, $d->getDay());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::isLeapYear
     */
    public function testIsLeapYear()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertTrue($d->isLeapYear());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::toDate
     */
    public function testToDate()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new Peach_DT_Date(2012, 5, 21), $d->toDate());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::toDatetime
     */
    public function testToDatetime()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new Peach_DT_Datetime(2012, 5, 21, 7, 30), $d->toDatetime());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::toTimestamp
     */
    public function testToTimestamp()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15), $d->toTimestamp());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     * @covers Peach_DT_TimeWrapper::__toString
     */
    public function test__toString()
    {
        $d = new Peach_DT_TimeWrapper(new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("2012-05-21 07:30:15", $d->__toString());
    }
}
?>