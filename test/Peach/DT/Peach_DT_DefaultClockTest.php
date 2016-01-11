<?php

class Peach_DT_DefaultClockTest extends PHPUnit_Framework_TestCase
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
     * getInstance() のテストです. 以下を確認します.
     * 
     * - DefaultClock オブジェクトを返す
     * - 複数回実行した際に同一のオブジェクトを返す
     * 
     * @covers Peach_DT_DefaultClock::getInstance
     */
    public function testGetInstance()
    {
        $obj1 = Peach_DT_DefaultClock::getInstance();
        $obj2 = Peach_DT_DefaultClock::getInstance();
        $this->assertInstanceOf("Peach_DT_DefaultClock", $obj1);
        $this->assertSame($obj1, $obj2);
    }
    
    /**
     * システム時刻と同じ unix time をあらわす Timestamp オブジェクトを返すことを確認します.
     *
     * @covers Peach_DT_DefaultClock::getUnixTime
     * @covers Peach_DT_Clock::getTimestamp
     */
    public function testGetTimestamp()
    {
        $obj    = Peach_DT_DefaultClock::getInstance();
        $now    = Peach_DT_UnixTimeFormat::getInstance()->parseTimestamp(time());
        $result = $obj->getTimestamp();
        $this->assertEquals($now, $result);
    }
}
