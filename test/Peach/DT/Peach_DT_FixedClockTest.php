<?php

class Peach_DT_FixedClockTest extends PHPUnit_Framework_TestCase
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
     * コンストラクタ引数に指定した unix time をそのまま返すことを確認します.
     * 
     * @covers Peach_DT_FixedClock::__construct
     * @covers Peach_DT_FixedClock::getUnixTime
     * @covers Peach_DT_Clock::getTimestamp
     */
    public function testGetTimestamp()
    {
        $obj    = new Peach_DT_FixedClock(1234567890);
        $result = $obj->getTimestamp();
        $this->assertEquals(new Peach_DT_Timestamp(2009, 2, 14, 8, 31, 30), $result);
    }
}
