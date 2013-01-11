<?php
require_once dirname(__FILE__) . '/../../src/DT/load.php';
require_once 'DT_AbstractTimeTest.php';

/**
 * Test class for DT_Timestamp.
 */
class DT_TimestampTest extends DT_AbstractTimeTest
{
    /**
     * 以下の確認を行います.
     * 
     * - フィールドの加減が正常に出来ること.
     * - 不正なフィールド名を指定した場合に無視されること.
     */
    public function testAdd()
    {
        $d1 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertEquals(new DT_Timestamp(2015, 5,  21,  7, 30, 15),  $d1->add("year",   3));
        $this->assertEquals(new DT_Timestamp(2009, 5,  21,  7, 30, 15),  $d1->add("year",  -3));
        $this->assertEquals(new DT_Timestamp(2012, 10, 21,  7, 30, 15),  $d1->add("month",  5));
        $this->assertEquals(new DT_Timestamp(2011, 12, 21,  7, 30, 15),  $d1->add("month", -5));
        $this->assertEquals(new DT_Timestamp(2012, 6,  10,  7, 30, 15),  $d1->add("date",  20));
        $this->assertEquals(new DT_Timestamp(2012, 4,  21,  7, 30, 15),  $d1->add("date", -30));
        $this->assertEquals(new DT_Timestamp(2012, 5,  21, 17, 30, 15),  $d1->add("hour",  10));
        $this->assertEquals(new DT_Timestamp(2012, 5,  20, 21, 30, 15),  $d1->add("hour", -10));
        $this->assertEquals(new DT_Timestamp(2012, 5,  21,  8, 15, 15),  $d1->add("min",   45));
        $this->assertEquals(new DT_Timestamp(2012, 5,  21,  6, 45, 15),  $d1->add("min ", -45));
        $this->assertEquals(new DT_Timestamp(2012, 5,  21,  7, 31, 15),  $d1->add("sec",   60));
        $this->assertEquals(new DT_Timestamp(2012, 5,  21,  7, 29, 45),  $d1->add("sec",  -30));
        
        $this->assertEquals(new DT_Timestamp(2012, 5,  21,  7, 30, 15),  $d1->add("asdf",  20));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 比較が正常に出来る
     * - 同じオブジェクトの場合は FALSE を返す
     * - 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが多いほうが「後」
     */
    public function testAfter()
    {
        $d1 = new DT_Timestamp(2012, 5, 21, 7, 30, 0);
        
        // 比較が正常にできる
        $this->assertTrue($d1->after(new DT_Timestamp(2012, 5, 21, 7, 29, 59)));
        $this->assertFalse($d1->after(new DT_Timestamp(2012, 5, 22, 6, 0, 0)));
        
        // 同じオブジェクトの場合は FALSE を返す
        $this->assertFalse($d1->after(new DT_Timestamp(2012, 5, 21, 7, 30, 0)));
        
        // 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが多いほうが「後」
        $this->assertTrue($d1->after(new DT_Date(2012, 5, 21)));
        $this->assertTrue($d1->after(new DT_Datetime(2012, 5, 21, 7, 30)));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 比較が正常に出来る
     * - 同じオブジェクトの場合は FALSE を返す
     * - 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが少ないほうが「前」
     * - DT_Time 以外のオブジェクトと比較した場合は FALSE を返す
     */
    public function testBefore()
    {
        $d1 = new DT_Timestamp(2012, 5, 21, 7, 30, 0);
        
        // 比較が正常にできる
        $this->assertFalse($d1->before(new DT_Timestamp(2011, 5, 21, 7, 29, 59)));
        $this->assertTrue($d1->before(new DT_Timestamp(2012, 5, 22, 6, 0, 0)));
        
        // 同じオブジェクトの場合は FALSE を返す
        $this->assertFalse($d1->before(new DT_Timestamp(2012, 5, 21, 7, 30, 0)));
        
        // 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが少ないほうが「前」
        $this->assertFalse($d1->before(new DT_Date(2012, 5, 21)));
        $this->assertFalse($d1->before(new DT_Datetime(2012, 5, 21, 7, 30)));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 比較が正常に出来る
     */
    public function testCompareTo()
    {
        $d = array(
            new DT_Timestamp(2012, 3, 12, 23, 59, 59),
            new DT_Timestamp(2012, 5, 21,  7, 30, 0),
            new DT_Timestamp(2013, 1, 23,  1, 23, 45),
        );
        $this->assertGreaterThan(0, $d[1]->compareTo($d[0]));
        $this->assertLessThan(0, $d[1]->compareTo($d[2]));
        $this->assertSame(0, $d[1]->compareTo($d[1]));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 同じ型で, 全てのフィールドの値が等しいオブジェクトの場合は TRUE
     * - 同じ型で, 一つ以上のフィールドの値が異なるオブジェクトの場合は FALSE
     * - 型が異なる場合は FALSE
     */
    public function testEquals()
    {
        $d1 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $d2 = new DT_Timestamp(2012, 5, 21, 7, 30, 45);
        $d3 = new DT_Date(2012, 5, 21);
        $w  = new DT_TimeWrapper($d1);
        $this->assertTrue($d1->equals($d1));
        $this->assertFalse($d1->equals($d2));
        $this->assertFalse($d1->equals($d3));
        $this->assertFalse($d1->equals($w));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 指定された Format オブジェクトの formatDatetime() メソッドを使って書式化されること
     * - 引数を省略した場合は __toString と同じ結果を返すこと
     */
    public function testFormat()
    {
        $d = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertSame("2012-05-21 07:30:15", $d->format());
        $this->assertSame("2012-05-21T07:30:15", $d->format(DT_W3CDatetimeFormat::getInstance()));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 年・月・日・時・分・秒のフィールドの取得が出来る
     * - 不正な引数を指定した場合は NULL を返す
     */
    public function testGet()
    {
        $time        = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $valid       = array();
        $valid[15]   = array("s", "S", "Sec", "sec", "smile"); // any string which starts with "s" is OK.
        $invalid = array(NULL, 1, "bar");
        foreach ($valid as $expected => $v) {
            foreach ($v as $key) {
                $this->assertEquals($time->get($key), $expected);
            }
        }
        foreach ($invalid as $key) {
            $this->assertNull($time->get($key));
        };
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 秒のフィールドの設定が出来る
     * - 不正な引数を指定した場合は同じオブジェクトを返す
     */
    public function testSet()
    {
        $time = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertEquals(
            array(
                new DT_Timestamp(2012, 5, 21, 7, 30, 25),
                new DT_Timestamp(2012, 5, 21, 7, 29, 40),
                new DT_Timestamp(2012, 5, 21, 7, 31, 10),
            ),
            array(
                $time->set("s",  25),
                $time->set("s", -20),
                $time->set("s",  70),
            )
        );
        $this->assertEquals($time, $time->set("foobar", 10));
    }
    
    /**
     * 以下を確認します.
     * 
     * - 配列を引数にして日付の設定が出来ること
     * - Util_Map を引数にして日付の設定が出来ること
     * - 範囲外のフィールドが指定された場合に, 上位のフィールドから順に調整されること
     * - 配列・Map 以外の型を指定した場合に例外をスローすること
     */
    public function testSetAll()
    {
        $d    = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $test = $d->setAll(array("min" => 34, "sec" => 59, "hour" => 6));
        $this->assertEquals(new DT_Timestamp(2012, 5, 21, 6, 34, 59), $test);
        
        $map  = new Util_ArrayMap();
        $map->put("mon", 2);
        $map->put("sec", 1);
        $map->put("min", 15);
        $test = $d->setAll($map);
        $this->assertEquals(new DT_Timestamp(2012, 2, 21, 7, 15, 1), $test);
        
        // 2012-05-21T-3:131:-85 => 2012-05-20T21:131:-85 => 2012-05-20T23:11:-85 => 2012-05-20T23:09:45
        $this->assertEquals(new DT_Timestamp(2012, 5, 20, 23, 9, 35), $d->setAll(array("hour" => -3, "min" => 131, "sec" => -85)));
        
        try {
            $test = $d->setAll("hoge");
            $this->fail();
        } catch (Exception $e) {}
    }
    
    /**
     * オブジェクトの各フィールドが現在時刻のそれに等しいかどうかを調べます.
     * このメソッドは, テストを開始するタイミングによって極稀に失敗する可能性があるため,
     * 失敗した場合は再度テストしてください.
     */
    public function testNow()
    {
        $d = DT_Timestamp::now();
        $this->assertSame(intval(date("Y")), $d->get("year"));
        $this->assertSame(intval(date("n")), $d->get("month"));
        $this->assertSame(intval(date("j")), $d->get("date"));
        $this->assertSame(intval(date("G")), $d->get("hour"));
        $this->assertSame(intval(date("i")), $d->get("min"));
        $this->assertSame(intval(date("s")), $d->get("sec"));
    }
    
    /**
     * parse に成功した場合に DT_Timestamp オブジェクト,
     * 失敗した場合に Exception をスローすることを確認します.
     */
    public function testParse()
    {
        $d = DT_Timestamp::parse("2011-05-21 07:30:15");
        $this->assertEquals(new DT_Timestamp(2011, 5, 21, 7, 30, 15), $d);
        try {
            $d = DT_Timestamp::parse("Illegal Format");
            $this->fail(); // Exception が発生しなかった場合は FAIL
        } catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }
    
    /**
     * {@link DT_Time::TYPE_TIMESTAMP} を返すことを確認します.
     */
    public function testGetType()
    {
        $d = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertSame(DT_Time::TYPE_TIMESTAMP, $d->getType());
    }
    
    /**
     * "hh:mm:ss" 形式の文字列を返すことを確認します.
     */
    public function testFormatTime()
    {
        $d = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertSame("07:30:15", $d->formatTime());
    }
    
    /**
     * DT_Timestamp から DT_Date へのキャストをテストします.
     */
    public function testToDate()
    {
        $d1 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertEquals(new DT_Date(2012, 5, 21), $d1->toDate());
    }
    
    /**
     * DT_Timestamp から DT_Datetime へのキャストをテストします.
     */
    public function testToDatetime()
    {
        $t1 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $this->assertEquals(new DT_Datetime(2012, 5, 21, 7, 30), $t1->toDatetime());
    }
    
    /**
     * DT_Timestamp から DT_Timestamp へのキャストをテストします.
     * 生成されたオブジェクトが, 元のオブジェクトのクローンであることを確認します.
     * すなわち, == による比較が TRUE, === による比較が FALSE となります.
     */
    public function testToTimestamp()
    {
        $t1 = new DT_Timestamp(2012, 5, 21, 7, 30, 15);
        $t2 = $t1->toTimestamp();
        $this->assertEquals($t1, $t2);
        $this->assertNotSame($t1, $t2);
    }
}
?>