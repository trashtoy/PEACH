<?php

require_once dirname(__FILE__) . '/../../src/DT/load.php';

/**
 * Test class for DT_TimeEquator.
 */
class DT_TimeEquatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * コンストラクタのテストです. コンストラクタの引数について以下を確認します.
     * 
     * - DT_Time::TYPE_DATE と array("year", "month", "date") が同一視されること
     * - DT_Time::TYPE_DATETIME と array("year", "month", "date", "hour", "minute") 
     *   が同一視されること
     * - DT_Time::TYPE_TIMESTAMP と
     *   array("year", "month", "date", "hour", "minute", "second")
     *   が同一視されること
     * - 文字列と array(文字列) が同一視されること
     */
    public function test__construct()
    {
        $this->assertEquals(
            new DT_TimeEquator(array("year", "month", "date")),
            new DT_TimeEquator(DT_Time::TYPE_DATE)
        );
        $this->assertEquals(
            new DT_TimeEquator(array("year", "month", "date", "hour", "minute")),
            new DT_TimeEquator(DT_Time::TYPE_DATETIME)
        );
        $this->assertEquals(
            new DT_TimeEquator(array("year", "month", "date", "hour", "minute", "second")),
            new DT_TimeEquator(DT_Time::TYPE_TIMESTAMP)
        );
        $this->assertEquals(
            new DT_TimeEquator(array("year")), new DT_TimeEquator("year")
        );
    }
    
    /**
     * 引数なしのコンストラクタで生成したインスタンスと等価のオブジェクトを返すことを確認します.
     */
    public function testGetDefault()
    {
        $this->assertEquals(new DT_TimeEquator(), DT_TimeEquator::getDefault());
    }
    
    /**
     * デフォルトの TimeEquator の場合, {@link DT_Time::equals()}
     * を使って比較を行うことを確認します.
     * 具体的には, 二つの時間オブジェクトの型が等しく, 
     * すべてのフィールドが同じ場合のみ TRUE となります.
     */
    public function testEquate1()
    {
        $e = DT_TimeEquator::getDefault();
        
        $d1 = new DT_Date(2012, 5, 21);
        $d2 = new DT_TimeWrapper($d1);
        $this->assertFalse($e->equate($d1, $d2));
        
        $d3 = new DT_Date(2012, 5, 21);
        $this->assertTrue($e->equate($d1, $d3));
        
        $d4 = new DT_Datetime(2012, 10, 31, 7, 30);
        $this->assertFalse($e->equate($d1, $d4));
    }
    
    /**
     * 引数を指定して初期化された TimeEquator が
     * 指定されたフィールドのみ比較することを確認します.
     * 
     * また, 未定義のフィールド (NULL) と 0 を明確に区別することも確認します.
     * array("hour", "minute", "second") で初期化された TimeEquator が
     * 0 時 0 分 0 秒の DT_Timestamp オブジェクトと,
     * 時・分・秒が未定義である DT_Date オブジェクトを比較した場合,
     * equate は FALSE を返します.
     */
    public function testEquate2()
    {
        $d1 = new DT_Timestamp(2012,  5, 21, 7, 30, 45);
        $d2 = new DT_Timestamp(2012, 12, 24, 7, 30, 45);
        $d3 = new DT_Timestamp(2012,  5, 21, 7, 34, 56);
        $d4 = new DT_Timestamp(2012,  5, 21, 0,  0,  0);
        $d5 = new DT_Date(2012, 5, 21);
        
        $e1 = new DT_TimeEquator(DT_Time::TYPE_DATE);
        $this->assertFalse($e1->equate($d1, $d2));
        $this->assertTrue($e1->equate($d1, $d3));
        $this->assertTrue($e1->equate($d1, $d5));
        
        $e2 = new DT_TimeEquator(array("hour", "minute", "second"));
        $this->assertTrue($e2->equate($d1, $d2));
        $this->assertFalse($e2->equate($d1, $d3));
        $this->assertFalse($e2->equate($d1, $d5));
        
        // 0 と null を区別するため FALSE を返す
        $this->assertFalse($e2->equate($d4, $d5));
    }
    
    /**
     * DT_Date, DT_Datetime, DT_Timestamp それぞれについて
     * 期待されたハッシュ値を算出していることを確認します.
     * 
     * また, DT_Time オブジェクト以外の引数を指定した場合に例外をスローすることを確認します.
     */
    public function testHashCode()
    {
        $d1 = new DT_Date(2012, 5, 21);
        $d2 = new DT_Datetime(2012, 5, 21, 7, 30);
        $d3 = new DT_Timestamp(2012, 5, 21, 7, 30, 45);
        
        $e  = DT_TimeEquator::getDefault();
        $this->assertSame(22348, $e->hashCode($d1));
        $this->assertSame(27936515, $e->hashCode($d2));
        $this->assertSame(1316248310, $e->hashCode($d3));
        try {
            $e->hashCode("asdf");
            $this->fail();
        } catch (Exception $e) {}
    }
}
?>