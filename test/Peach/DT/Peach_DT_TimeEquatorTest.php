<?php
/**
 * Test class for Peach_DT_TimeEquator.
 */
class Peach_DT_TimeEquatorTest extends PHPUnit_Framework_TestCase
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
     * コンストラクタのテストです. コンストラクタの引数について以下を確認します.
     * 
     * - Peach_DT_Time::TYPE_DATE と array("year", "month", "date") が同一視されること
     * - Peach_DT_Time::TYPE_DATETIME と
     *   array("year", "month", "date", "hour", "minute") 
     *   が同一視されること
     * - Peach_DT_Time::TYPE_TIMESTAMP と
     *   array("year", "month", "date", "hour", "minute", "second")
     *   が同一視されること
     * - 文字列と array(文字列) が同一視されること
     * 
     * @covers Peach_DT_TimeEquator::__construct
     */
    public function test__construct()
    {
        $this->assertEquals(
            new Peach_DT_TimeEquator(array("year", "month", "date")),
            new Peach_DT_TimeEquator(Peach_DT_Time::TYPE_DATE)
        );
        $this->assertEquals(
            new Peach_DT_TimeEquator(array("year", "month", "date", "hour", "minute")),
            new Peach_DT_TimeEquator(Peach_DT_Time::TYPE_DATETIME)
        );
        $this->assertEquals(
            new Peach_DT_TimeEquator(array("year", "month", "date", "hour", "minute", "second")),
            new Peach_DT_TimeEquator(Peach_DT_Time::TYPE_TIMESTAMP)
        );
        $this->assertEquals(
            new Peach_DT_TimeEquator(array("year")), new Peach_DT_TimeEquator("year")
        );
    }
    
    /**
     * 引数なしのコンストラクタで生成したインスタンスと等価のオブジェクトを返すことを確認します.
     * @covers Peach_DT_TimeEquator::getDefault
     */
    public function testGetDefault()
    {
        $this->assertEquals(new Peach_DT_TimeEquator(), Peach_DT_TimeEquator::getDefault());
    }
    
    /**
     * デフォルトの TimeEquator の場合, {@link Peach_DT_Time::equals()}
     * を使って比較を行うことを確認します.
     * 具体的には, 二つの時間オブジェクトの型が等しく, 
     * すべてのフィールドが同じ場合のみ TRUE となります.
     * 
     * @covers Peach_DT_TimeEquator::equate
     */
    public function testEquate1()
    {
        $e = Peach_DT_TimeEquator::getDefault();
        
        $d1 = new Peach_DT_Date(2012, 5, 21);
        $d2 = new Peach_DT_TimeWrapper($d1);
        $this->assertFalse($e->equate($d1, $d2));
        
        $d3 = new Peach_DT_Date(2012, 5, 21);
        $this->assertTrue($e->equate($d1, $d3));
        
        $d4 = new Peach_DT_Datetime(2012, 10, 31, 7, 30);
        $this->assertFalse($e->equate($d1, $d4));
    }
    
    /**
     * 引数を指定して初期化された TimeEquator が
     * 指定されたフィールドのみ比較することを確認します.
     * 
     * また, 未定義のフィールド (NULL) と 0 を明確に区別することも確認します.
     * array("hour", "minute", "second") で初期化された TimeEquator が
     * 0 時 0 分 0 秒の Peach_DT_Timestamp オブジェクトと,
     * 時・分・秒が未定義である Peach_DT_Date オブジェクトを比較した場合,
     * equate は FALSE を返します.
     * 
     * @covers Peach_DT_TimeEquator::equate
     */
    public function testEquate2()
    {
        $d1 = new Peach_DT_Timestamp(2012,  5, 21, 7, 30, 45);
        $d2 = new Peach_DT_Timestamp(2012, 12, 24, 7, 30, 45);
        $d3 = new Peach_DT_Timestamp(2012,  5, 21, 7, 34, 56);
        $d4 = new Peach_DT_Timestamp(2012,  5, 21, 0,  0,  0);
        $d5 = new Peach_DT_Date(2012, 5, 21);
        
        $e1 = new Peach_DT_TimeEquator(Peach_DT_Time::TYPE_DATE);
        $this->assertFalse($e1->equate($d1, $d2));
        $this->assertTrue($e1->equate($d1, $d3));
        $this->assertTrue($e1->equate($d1, $d5));
        
        $e2 = new Peach_DT_TimeEquator(array("hour", "minute", "second"));
        $this->assertTrue($e2->equate($d1, $d2));
        $this->assertFalse($e2->equate($d1, $d3));
        $this->assertFalse($e2->equate($d1, $d5));
        
        // 0 と null を区別するため FALSE を返す
        $this->assertFalse($e2->equate($d4, $d5));
    }
    
    /**
     * Peach_DT_Date, Peach_DT_Datetime, Peach_DT_Timestamp それぞれについて
     * 期待されたハッシュ値を算出していることを確認します.
     * 
     * また, Peach_DT_Time オブジェクト以外の引数を指定した場合に例外をスローすることを確認します.
     * 
     * @covers Peach_DT_TimeEquator::hashCode
     */
    public function testHashCode()
    {
        $d1 = new Peach_DT_Date(2012, 5, 21);
        $d2 = new Peach_DT_Datetime(2012, 5, 21, 7, 30);
        $d3 = new Peach_DT_Timestamp(2012, 5, 21, 7, 30, 45);
        
        $e  = Peach_DT_TimeEquator::getDefault();
        $this->assertSame(22348, $e->hashCode($d1));
        $this->assertSame(27936515, $e->hashCode($d2));
        $this->assertSame(1316248310, $e->hashCode($d3));
        try {
            $e->hashCode("asdf");
            $this->fail();
        } catch (Exception $e) {
            $this->assertSame("Exception", get_class($e));
        }
    }
}
