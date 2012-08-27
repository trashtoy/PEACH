<?php
require_once 'DT/load.php';
require_once 'DT_AbstractTimeTest.php';
/**
 * Test class for DT_Date.
 */
class DT_DateTest extends DT_AbstractTimeTest {
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * オブジェクトの各フィールドが現在時刻のそれに等しいかどうかを調べます.
     * このメソッドは, テストを開始するタイミングによって極稀に失敗する可能性があるため,
     * 失敗した場合は再度テストしてください.
     */
    public function testNow() {
        $d = DT_Date::now();
        $this->assertEquals($d->get("year"),  date("Y"));
        $this->assertEquals($d->get("month"), date("n"));
        $this->assertEquals($d->get("date"),  date("j"));
    }
    
    /**
     * parse に成功した場合に DT_Date オブジェクト,
     * 失敗した場合に Exception をスローすることを確認します.
     */
    public function testParse() {
        $d = DT_Date::parse("2011-05-21");
        $this->assertTrue($d instanceof DT_Date);
        try {
            $d = DT_Date::parse("Illegal Format");
            $this->fail(); // Exception が発生しなかった場合は FAIL
        }
        catch (Exception $e) {
            $this->assertTrue($e instanceof Exception);
        }
    }
    
    /**
     * 形式が "YYYY-MM-DD" になっていることを確認します.
     * 特にフィールドが1桁の場合に '0' が付加されていることを確認します.
     */
    public function test__toString() {
        $d = array(
            new DT_Date(2011,  5,  1),
            new DT_Date(2000, 12, 31),
            new DT_Date(1863, 11, 19)
        );
        $f = array(
            "2011-05-01",
            "2000-12-31",
            "1863-11-19"
        );
        for ($i = 0; $i < 3; $i ++) {
            $this->assertEquals($d[$i]->__toString(), $f[$i]);
        }
    }
    
    /**
     * Date から Date へのキャストをテストします.
     * 生成されたオブジェクトが, 元のオブジェクトのクローンであることを確認します.
     * すなわち, == による比較が TRUE, === による比較が FALSE となります.
     */
    public function testToDate() {
        $d1 = new DT_Date(2011, 5, 21);
        $d2 = $d1->toDate();
        $this->assertEquals($d1, $d2);
        $this->assertNotSame($d1, $d2);
    }
    
    /**
     * Date から Datetime へのキャストをテストします.
     * 生成されたオブジェクトについて, 以下の点を確認します.
     * 
     * - 年・月・日のフィールドが元のオブジェクトのものと等しい
     * - 時・分のフィールドが 0 になっている
     */
    public function testToDatetime() {
        $d1 = new DT_Date(2011, 5, 21);
        $d2 = $d1->toDatetime();
        $this->assertSame($d2->get("year"),  2011);
        $this->assertsame($d2->get("month"), 5);
        $this->assertSame($d2->get("date"),  21);
        $this->assertSame($d2->get("hour"),  0);
        $this->assertSame($d2->get("min"),   0);
        $this->assertNull($d2->get("sec"));
    }
    
    /**
     * Date から Timestamp へのキャストをテストします.
     * 生成されたオブジェクトについて, 以下の点を確認します.
     * 
     * - 年・月・日のフィールドが元のオブジェクトのものと等しい
     * - 時・分・秒のフィールドが 0 になっている
     */
    public function testToTimestamp() {
        $d1 = new DT_Date(2011, 5, 21);
        $d2 = $d1->toTimestamp();
        $this->assertSame($d2->get("year"),  2011);
        $this->assertsame($d2->get("month"), 5);
        $this->assertSame($d2->get("date"),  21);
        $this->assertSame($d2->get("hour"),  0);
        $this->assertSame($d2->get("min"),   0);
        $this->assertSame($d2->get("sec"),   0);
    }
    
    /**
     * 正しい曜日が取得できるかどうかテストします.
     */
    public function testGetDay() {
        $sample = array(
            new DT_Date(1990, 4, 1),
            new DT_Date(1996, 3, 18),
            new DT_Date(1999, 4, 6),
            new DT_Date(2002, 7, 10),
            new DT_Date(2005, 10, 27),
            new DT_Date(2008, 6, 13),
            new DT_Date(2010, 7, 24)
        );
        for ($i = 0; $i < 7; $i ++) {
            $this->assertEquals($sample[$i]->getDay(), $i);
        }
    }
    
    /**
     * うるう年の判定ロジックのテストです.
     * 年のフィールドについて以下を確認します.
     * 
     * - 400 の倍数の時: TRUE
     * - 100 の倍数の時: FALSE
     * - 4 の倍数の時:   TRUE
     * - それ以外:       FALSE
     */
    public function testIsLeapYear() {
        $date = DT_Date::now();
        $d1   = $date->set("y", 2011);
        $d2   = $date->set("y", 2008);
        $d3   = $date->set("y", 2100);
        $d4   = $date->set("y", 2000);
        
        $this->assertFalse($d1->isLeapYear());
        $this->assertTrue($d2->isLeapYear());
        $this->assertFalse($d3->isLeapYear());
        $this->assertTrue($d4->isLeapYear());
    }
    
    /**
     * 日数計算のテストを行います.
     * 
     */
    public function testGetDateCount() {
        $d1 = new DT_Date(2011, 7, 8);
        $d2 = new DT_Date(2009, 11, 12);
        $d3 = new DT_Date(2010, 2, 4);
        $d4 = new DT_Date(2012, 2, 3);
        
        $this->assertSame($d1->getDateCount(), 31);
        $this->assertSame($d2->getDateCount(), 30);
        $this->assertSame($d3->getDateCount(), 28);
        $this->assertSame($d4->getDateCount(), 29);
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - フィールドの加減が正常に出来ること.
     * - 不正なフィールド名を指定した場合に無視されること.
     */
    public function testAdd() {
        $d1 = new DT_Date(2012, 5, 21);
        $this->assertEquals(new DT_Date(2015, 5,  21),  $d1->add("year",   3));
        $this->assertEquals(new DT_Date(2009, 5,  21),  $d1->add("year",  -3));
        $this->assertEquals(new DT_Date(2012, 10, 21),  $d1->add("month",  5));
        $this->assertEquals(new DT_Date(2011, 12, 21),  $d1->add("month", -5));
        $this->assertEquals(new DT_Date(2012, 6,  10),  $d1->add("date",  20));
        $this->assertEquals(new DT_Date(2012, 4,  21),  $d1->add("date", -30));
        
        $this->assertEquals(new DT_Date(2012, 5,  21),  $d1->add("min",   10));
        $this->assertEquals(new DT_Date(2012, 5,  21),  $d1->add("sec",  -10));
        $this->assertEquals(new DT_Date(2012, 5,  21),  $d1->add("hoge",  20));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 比較が正常に出来る
     * - 同じオブジェクトの場合は FALSE を返す
     * - 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが多いほうが「後」
     */
    public function testAfter() {
        $d1 = new DT_Date(2012, 5, 21);
        
        // 比較が正常にできる
        $this->assertTrue($d1->after(new DT_Date(2011, 12, 31)));
        $this->assertFalse($d1->after(new DT_Date(2013, 3, 1)));
        
        // 同じオブジェクトの場合は FALSE を返す
        $this->assertFalse($d1->after(new DT_Date(2012, 5, 21)));
        
        // 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが多いほうが「後」
        $this->assertFalse($d1->after(new DT_Datetime(2012, 5, 21, 0, 0)));
        $this->assertFalse($d1->after(new DT_Timestamp(2012, 5, 21, 0, 0, 0)));
    }
    
    /**
     * 以下の確認を行います.
     * 
     * - 比較が正常に出来る
     * - 同じオブジェクトの場合は FALSE を返す
     * - 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが少ないほうが「前」
     * - DT_Time 以外のオブジェクトと比較した場合は FALSE を返す
     */
    public function testBefore() {
        $d1 = new DT_Date(2012, 5, 21);
        
        // 比較が正常にできる
        $this->assertFalse($d1->before(new DT_Date(2011, 12, 31)));
        $this->assertTrue($d1->before(new DT_Date(2013, 3, 1)));
        
        // 同じオブジェクトの場合は FALSE を返す
        $this->assertFalse($d1->before(new DT_Date(2012, 5, 21)));
        
        // 異なる型との比較で, 共通のフィールドが全て等しい場合は, フィールドが少ないほうが「前」
        $this->assertTrue($d1->before(new DT_Datetime(2012, 5, 21, 0, 0)));
        $this->assertTrue($d1->before(new DT_Timestamp(2012, 5, 21, 0, 0, 0)));
    }
    
    public function testCompareTo() {
        $d = array(
            new DT_Date(2012, 3, 12),
            new DT_Date(2012, 5, 21),
            new DT_Date(2013, 1, 23),
        );
        $this->assertGreaterThan(0, $d[1]->compareTo($d[0]));
        $this->assertLessThan(0, $d[1]->compareTo($d[2]));
        $this->assertSame(0, $d[1]->compareTo($d[1]));
    }
    
    public function testEquals() {
        $d = new DT_Date(2012, 5, 21);
        $t = new DT_Timestamp(2012, 5, 21, 7, 30, 0);
        $w = new DT_TimeWrapper($d);
        $this->assertTrue($d->equals($d));
        $this->assertFalse($d->equals($t));
        $this->assertFalse($d->equals($w));
    }
    
    public function testFormat() {
        $d = new DT_Date(2012, 5, 21);
        $this->assertSame("2012-05-21", $d->format());
        $this->assertSame("2012-05-21", $d->format(DT_W3CDatetimeFormat::getDefault()));
    }
    
    public function testFormatTime() {
        $d = new DT_Date(2012, 5, 21);
        $this->assertSame("", $d->formatTime());
    }
    
    public function testGet() {
        $time        = new DT_Date(2012, 5, 21);
        $valid       = array();
        $valid[2012] = array('y', 'Y', 'year', 'YEAR', 'young', 'Yacht'); // any string which starts with "Y" or "y" is OK.
        $valid[5]    = array('MO', 'mo', 'Month', 'month', 'monkey');     // any string which starts with "mo" is OK.
        $valid[21]   = array('d', 'D', 'DATE', 'dog');                    // any string which starts with "d" is OK.
        $invalid = array("m", "hour", "min", "sec", NULL, "foo");
        foreach ($valid as $expected => $v) {
            foreach ($v as $key) {
                $this->assertEquals($time->get($key), $expected);
            }
        }
        foreach ($invalid as $key) {
            $this->assertNull($time->get($key));
        };
    }
    
    public function testSet() {
        $time = new DT_Date(2012, 5, 21);
        $y    = array();
        $y[]  = $time->set("y", 2013);
        $y[]  = $time->set("y", 10000);
        $y[]  = $time->set("y", 999);
        $y[]  = $time->set("y", -1);
        $exY  = array(
            new DT_Date(2013, 5, 21),
            new DT_Date(0,    5, 21),
            new DT_Date(999,  5, 21),
            new DT_Date(9999, 5, 21)
        );
        for ($i = 0; $i < 4; $i ++) {
            $this->assertEquals($exY[$i], $y[$i]);
        }
        
        $m    = array();
        $m[]  = $time->set("mo", 10);
        $m[]  = $time->set("mo", 13);  // 2012-13 => 2013-01
        $m[]  = $time->set("mo", 26);  // 2012-26 => 2013-14 => 2014-02
        $m[]  = $time->set("mo", 0);   // 2012-00 => 2011-12
        $m[]  = $time->set("mo", -13); // 2010-11
        $exM  = array(
            new DT_Date(2012, 10, 21),
            new DT_Date(2013,  1, 21),
            new DT_Date(2014,  2, 21),
            new DT_Date(2011, 12, 21),
            new DT_Date(2010, 11, 21)
        );
        for ($i = 0; $i < 5; $i ++) {
            $this->assertEquals($exM[$i], $m[$i]);
        }
        
        $d   = array();
        $d[] = $time->set("d", 32);
        $d[] = $time->set("d", 0);
        $d[] = $time->set("d", -29);
        $d[] = $time->set("d", 64);
        $d[] = $time->set("d", -59);
        $exD = array(
            new DT_Date(2012, 6, 1),
            new DT_Date(2012, 4, 30),
            new DT_Date(2012, 4, 1),
            new DT_Date(2012, 7, 3),
            new DT_Date(2012, 3, 2)
        );
        for ($i = 0; $i < 5; $i ++) {
            $this->assertEquals($exD[$i], $d[$i]);
        }
    }
    
    /**
     * 以下を確認します.
     * 
     * - 配列を引数にして日付の設定が出来ること
     * - Util_Map を引数にして日付の設定が出来ること
     * - 配列・Map 以外の型を指定した場合に例外をスローすること
     */
    public function testSetAll() {
        $d    = new DT_Date(2012, 5, 21);
        $test = $d->setAll(array("year" => 2015, "date" => 10));
        $this->assertEquals(new DT_Date(2015, 5, 10), $test);
        
        $map  = new Util_ArrayMap();
        $map->put("month", 12);
        $map->put("date",  31);
        $test = $d->setAll($map);
        $this->assertEquals(new DT_Date(2012, 12, 31), $test);
        
        try {
            $test = $d->setAll("hoge");
            $this->fail();
        }
        catch (Exception $e) {}
    }
}
?>