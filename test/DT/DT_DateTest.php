<?php
require_once 'DT/load.php';

/**
 * Test class for DT_Date.
 */
class DT_DateTest extends AbstractDT_TimeTest {
    /**
     * @var DT_Date
     */
    protected $object;
    
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
     * このメソッドは, テストを開始するタイミングによって失敗する可能性があるため,
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
     * 
     */
    public function testAdd() {
        ;
    }
    
    public function testAfter() {
        ;
    }
    
    public function testBefore() {
        ;
    }
    
    public function testCompareTo() {
        ;
    }
    
    public function testEquals() {
        ;
    }
    
    public function testFormat() {
        ;
    }
    
    public function testFormatTime() {
        ;
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
    
    public function testSetAll() {
        ;
    }
}

?>