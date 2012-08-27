<?php
require_once 'DT/load.php';
/**
 * Test class for DT_Util.
 */
class DT_UtilTest extends PHPUnit_Framework_TestCase {
    private function getTestArray() {
        $d   = array();
        $d[] = new DT_Date(     2012, 3,  29);
        $d[] = new DT_Datetime( 2012, 3,  29, 21, 59);
        $d[] = new DT_Timestamp(2012, 3,  29, 21, 59, 59);
        $d[] = new DT_Date(     2012, 5,  21);
        $d[] = new DT_Datetime( 2012, 5,  21,  7, 30);
        $d[] = new DT_Timestamp(2012, 5,  21,  7, 30, 15);
        $d[] = new DT_Date(     2012, 10,  5);
        $d[] = new DT_Datetime( 2012, 10,  5, 19,  0);
        $d[] = new DT_Timestamp(2012, 10,  5, 19,  0, 30);
        return $d;
    }
    
    /**
     * - 正しく比較が行えることを確認します
     * - 異なる型の比較で共通フィールドが全て等しかった場合, より上位の型を大とします.
     */
    public function testCompareTime() {
        $d   = $this->getTestArray();
        
        // 同じオブジェクト同士の比較は 0 を返します
        for ($i = 0; $i < 9; $i ++) {
            $this->assertSame(0, DT_Util::compareTime($d[$i], $d[$i]));
        }
        // 同じ型同士の比較が正しく出来ることを確認します
        for ($i = 0; $i < 3; $i ++) {
            $this->assertLessThan(0, DT_Util::compareTime($d[$i],     $d[$i + 3]));
            $this->assertLessThan(0, DT_Util::compareTime($d[$i + 3], $d[$i + 6]));
            $this->assertGreaterThan(0, DT_Util::compareTime($d[$i + 6], $d[$i]));
        }
        // 異なる型同士で正しく比較できることを確認します
        // 共通フィールドすべてが等しい場合は、より多くのフィールドを持つほうが大となります
        for ($i = 0; $i < 8; $i ++) {
            $this->assertLessThan(0, DT_Util::compareTime($d[$i], $d[$i + 1]));
            $this->assertGreaterThan(0, DT_Util::compareTime($d[$i + 1], $d[$i]));
        }
        
        /*
         * ラッパーオブジェクトを含めたテスト
         */
        $d   = array();
        $d[] = new DT_Date(2012, 5, 21);
        $d[] = new DT_Datetime(2012, 5, 21, 0, 0);
        $d[] = new DT_Timestamp(2012, 5, 21, 0, 0, 0);
        $d[] = new DT_TimeWrapper($d[0]);
        $d[] = new DT_TimeWrapper($d[1]);
        $d[] = new DT_TimeWrapper($d[2]);
        
        $zeroTest1 = array(0, 1, 2, 3, 4, 5);
        $zeroTest2 = array(3, 4, 5, 0, 1, 2);
        $subTest1  = array(1, 2, 2, 4, 5, 5);
        $subTest2  = array(0, 0, 1, 3, 3, 4);
        $subTest3  = array(3, 3, 4, 0, 0, 1);

        for ($i = 0; $i < 6; $i ++) {
            $i1 = $zeroTest1[$i];
            $i2 = $zeroTest2[$i];
            $i3 = $subTest1[$i];
            $i4 = $subTest2[$i];
            $i5 = $subTest3[$i];
            
            // あるオブジェクトと、そのオブジェクトのラッパーオブジェクトの比較は 0 を返します
            $this->assertSame(0, DT_Util::compareTime($d[$i1], $d[$i2]));
            $this->assertSame(0, DT_Util::compareTime($d[$i2], $d[$i1]));
            
            // 共通フィールドがすべて等しい場合、より多くのフィールドを持つほうが大となります
            $this->assertGreaterThan(0, DT_Util::compareTime($d[$i3], $d[$i4]));
            $this->assertGreaterThan(0, DT_Util::compareTime($d[$i3], $d[$i5]));
            $this->assertLessThan(0, DT_Util::compareTime($d[$i4], $d[$i3]));
            $this->assertLessThan(0, DT_Util::compareTime($d[$i5], $d[$i3]));
        }
    }

    /**
     * 以下を確認します.
     * 
     * - 配列を引数にして実行できること
     * - 引数を羅列して実行できること
     * - 引数に不正な型を含む場合でも正常に動作すること
     */
    public function testOldest() {
        $d = $this->getTestArray();
        $this->assertSame($d[0], DT_Util::oldest($d));
        $this->assertSame($d[1], DT_Util::oldest($d[4], $d[1], $d[3], $d[5], $d[8]));
        $this->assertSame($d[2], DT_Util::oldest($d[5], NULL, $d[2], 128, $d[3]));
    }

    /**
     * 以下を確認します.
     * 
     * - 配列を引数にして実行できること
     * - 引数を羅列して実行できること
     * - 引数に不正な型を含む場合でも正常に動作すること
     */
    public function testLatest() {
        $d = $this->getTestArray();
        $this->assertSame($d[8], DT_Util::latest($d));
        $this->assertSame($d[6], DT_Util::latest($d[4], $d[2], $d[6], $d[0], $d[5]));
        $this->assertSame($d[7], DT_Util::latest($d[1], 256, $d[7], FALSE, $d[3]));
    }
}
?>