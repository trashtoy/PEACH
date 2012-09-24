<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';
require_once 'DT_AbstractTimeTest.php';

/**
 * Test class for DT_TimeWrapper.
 */
class DT_TimeWrapperTest extends DT_AbstractTimeTest {
    /**
     * コンストラクタの引数と等しい時間オブジェクトを返すことを確認します.
     */
    public function testGetOriginal() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 7, 30, 15), $d->getOriginal());
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testGetType() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(DT_Time::TYPE_TIMESTAMP, $d->getType());
    }
    
    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testBefore() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertTrue($d->before(new DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertFalse($d->before(new DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testAfter() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertFalse($d->after(new DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertTrue($d->after(new DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testCompareTo() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertLessThan(0, $d->compareTo(new DT_Timestamp(2012, 5, 22, 0, 0, 0)));
        $this->assertGreaterThan(0, $d->compareTo(new DT_Timestamp(2012, 5, 20, 0, 0, 0)));
    }

    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     */
    public function testAdd() {
        $d1 = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->add("hour", 18);
        $this->assertEquals(new DT_Timestamp(2012, 5, 22, 1, 30, 15), $d2->toTimestamp());
        $this->assertType("DT_TimeWrapper", $d2);
    }

    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     */
    public function testSet() {
        $d1 = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->set("min", 80);
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 8, 20, 15), $d2->toTimestamp());
        $this->assertType("DT_TimeWrapper", $d2);
    }

    /**
     * 以下を確認します.
     * 
     * - 内部フィールドが正常に変化すること
     * - 返り値がこのクラスのオブジェクトになること
     */
    public function testSetAll() {
        $d1 = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = $d1->setAll(array("year" => 2013, "month" => -1, "date" => 32, "sec" => 87));
        $this->assertEquals(new DT_Timestamp(2012, 12, 2, 7, 31, 27), $d2->toTimestamp());
        $this->assertType("DT_TimeWrapper", $d2);
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testGet() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame(21, $d->get("date"));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testFormat() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("2012-05-21 07:30:15", $d->format());
        $this->assertSame("2012-05-21T07:30:15", $d->format(DT_W3CDatetimeFormat::getDefault()));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testFormatTime() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("07:30:15", $d->formatTime());
    }

    /**
     * 以下を確認します.
     * 
     * - クラスが異なる場合は FALSE
     * - クラスが同じで, フィールドの比較結果が 0 の場合は TRUE
     */
    public function testEquals() {
        $d1 = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $d2 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $d3 = new DT_TimeWrapper(new DT_Timestamp(2012, 1, 1, 0, 0, 0));
        $d4 = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertFalse($d1->equals($d2));
        $this->assertFalse($d1->equals($d3));
        $this->assertTrue($d1->equals($d4));
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testToDate() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new DT_Date(2012, 5, 21), $d->toDate());
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testToDatetime() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new DT_Datetime(2012, 5, 21, 7, 30), $d->toDatetime());
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function testToTimestamp() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 7, 30, 15), $d->toTimestamp());
    }

    /**
     * ラップ対象のオブジェクトと同じ結果を返すことを確認します.
     */
    public function test__toString() {
        $d = new DT_TimeWrapper(new DT_Timestamp(2012, 5, 21, 7, 30, 15));
        $this->assertSame("2012-05-21 07:30:15", $d->__toString());
    }
}
?>