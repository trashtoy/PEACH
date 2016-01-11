<?php

class Peach_DT_OffsetClockTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    private $defaultTZ;
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->defaultTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Tokyo");
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        date_default_timezone_set($this->defaultTZ);
    }
    
    /**
     * コンストラクタの第 2 引数を指定しない場合は DefaultClock が適用されることを確認します.
     * 
     * @covers Peach_DT_OffsetClock::__construct
     * @covers Peach_DT_OffsetClock::getUnixTime
     * @covers Peach_DT_Clock::getTimestamp
     */
    public function testGetTimestampByDefault()
    {
        $obj   = new Peach_DT_OffsetClock(-3600);
        $date1 = $obj->getTimestamp();
        
        $now   = time();
        $shift = $now - 3600;
        $date2 = Peach_DT_UnixTimeFormat::getInstance()->parseTimestamp($shift);
        
        $this->assertEquals($date2, $date1);
    }
    
    /**
     * コンストラクタの第 2 引数の Clock オブジェクトを基準として現在時刻が移動することを確認します.
     * 
     * @covers Peach_DT_OffsetClock::__construct
     * @covers Peach_DT_OffsetClock::getUnixTime
     * @covers Peach_DT_Clock::getTimestamp
     */
    public function testGetTimestamp()
    {
        $base  = new Peach_DT_FixedClock(1234567890);
        $obj   = new Peach_DT_OffsetClock(1800, $base);
        $date1 = $obj->getTimestamp();
        
        $shift = 1234567890 + 1800;
        $date2 = Peach_DT_UnixTimeFormat::getInstance()->parseTimestamp($shift);
        
        $this->assertEquals($date2, $date1);
    }
}
