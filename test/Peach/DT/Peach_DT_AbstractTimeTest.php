<?php
abstract class Peach_DT_AbstractTimeTest extends PHPUnit_Framework_TestCase
{
    public abstract function testGet();
    
    public abstract function testSet();
    
    public abstract function testSetAll();
    
    public abstract function testAdd();
    
    public abstract function testCompareTo();
    
    public abstract function testFormat();
    
    public abstract function testEquals();
    
    public abstract function testBefore();
    
    public abstract function testAfter();
    
    public abstract function testFormatTime();
}
