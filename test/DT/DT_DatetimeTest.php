<?php

require_once 'DT/load.php';
require_once 'DT_AbstractTimeTest.php';

/**
 * Test class for DT_Datetime.
 */
class DT_DatetimeTest extends DT_AbstractTimeTest {  
    /**
     * 以下の確認を行います.
     * 
     * - フィールドの加減が正常に出来ること.
     * - 不正なフィールド名を指定した場合に無視されること.
     */
    public function testAdd() {
        $d1 = new DT_Datetime(2012, 5, 21, 7, 30);
        $this->assertEquals(new DT_Datetime(2015, 5,  21,  7, 30),  $d1->add("year",   3));
        $this->assertEquals(new DT_Datetime(2009, 5,  21,  7, 30),  $d1->add("year",  -3));
        $this->assertEquals(new DT_Datetime(2012, 10, 21,  7, 30),  $d1->add("month",  5));
        $this->assertEquals(new DT_Datetime(2011, 12, 21,  7, 30),  $d1->add("month", -5));
        $this->assertEquals(new DT_Datetime(2012, 6,  10,  7, 30),  $d1->add("date",  20));
        $this->assertEquals(new DT_Datetime(2012, 4,  21,  7, 30),  $d1->add("date", -30));
        $this->assertEquals(new DT_Datetime(2012, 5,  21, 17, 30),  $d1->add("hour",  10));
        $this->assertEquals(new DT_Datetime(2012, 5,  20, 21, 30),  $d1->add("hour", -10));
        $this->assertEquals(new DT_Datetime(2012, 5,  21,  8, 15),  $d1->add("min",   45));
        $this->assertEquals(new DT_Datetime(2012, 5,  21,  6, 45),  $d1->add("min ", -45));
        
        $this->assertEquals(new DT_Datetime(2012, 5,  21,  7, 30),  $d1->add("sec",  -10));
        $this->assertEquals(new DT_Datetime(2012, 5,  21,  7, 30),  $d1->add("asdf",  20));
    }
    
    public function testAfter() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testBefore() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testCompareTo() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testEquals() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testFormat() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testGet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testSet() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    public function testSetAll() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }
    
    /**
     * オブジェクトの各フィールドが現在時刻のそれに等しいかどうかを調べます.
     * このメソッドは, テストを開始するタイミングによって極稀に失敗する可能性があるため,
     * 失敗した場合は再度テストしてください.
     */
    public function testNow() {
        $d = DT_Datetime::now();
        $this->assertSame(intval(date("Y")), $d->get("year"));
        $this->assertSame(intval(date("n")), $d->get("month"));
        $this->assertSame(intval(date("j")), $d->get("date"));
        $this->assertSame(intval(date("G")), $d->get("hour"));
        $this->assertSame(intval(date("i")), $d->get("min"));
    }
    
    /**
     * parse に成功した場合に DT_Datetime オブジェクト,
     * 失敗した場合に Exception をスローすることを確認します.
     */
    public function testParse() {
        $d = DT_Datetime::parse("2011-05-21 07:30");
        $this->assertEquals(new DT_Datetime(2011, 5, 21, 7, 30), $d);
        try {
            $d = DT_Datetime::parse("Illegal Format");
            $this->fail(); // Exception が発生しなかった場合は FAIL
        }
        catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }

    /**
     * {@link DT_Time::TYPE_DATETIME} を返すことを確認します.
     */
    public function testGetType() {
        $d = new DT_Datetime(2012, 5, 21, 7, 30);
        $this->assertSame(DT_Time::TYPE_DATETIME, $d->getType());
    }

    /**
     * "mm:dd" 形式の文字列を返すことを確認します.
     */
    public function testFormatTime() {
        $d = new DT_Datetime(2012, 5, 21, 7, 30);
        $this->assertSame("07:30", $d->formatTime());
    }

    /**
     * 形式が "YYYY-MM-DD hh:mm" 形式になっていることを確認します.
     */
    public function test__toString() {
        $t = new DT_Datetime(2012, 5, 21, 7, 30);
        $this->assertSame("2012-05-21 07:30", $t->__toString());

    }
    
    /**
     * Datetime から Datetime へのキャストをテストします.
     * 生成されたオブジェクトが, 元のオブジェクトのクローンであることを確認します.
     * すなわち, == による比較が TRUE, === による比較が FALSE となります.
     */
    public function testToDatetime() {
        $t1 = new DT_Datetime(2012, 5, 21, 7, 30);
        $t2 = $t1->toDatetime();
        $this->assertEquals($t1, $t2);
        $this->assertNotSame($t1, $t2);
    }

    /**
     * @todo Implement testToTimestamp().
     */
    public function testToTimestamp() {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
                'This test has not been implemented yet.'
        );
    }

}

?>
