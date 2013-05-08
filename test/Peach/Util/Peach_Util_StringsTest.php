<?php
class Peach_Util_StringsTest extends PHPUnit_Framework_TestCase
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
     * @covers Peach_Util_Strings::explode
     */
    public function testExplode()
    {
        $this->assertSame(array("A","B","C"), Peach_Util_Strings::explode("-", "A-B-C"));
        $this->assertSame(array(), Peach_Util_Strings::explode("", "A-B-C"));
        $obj = new Peach_Util_StringsTest_Object("A-B-C");
        $this->assertSame(array("A","B","C"), Peach_Util_Strings::explode("-", $obj));
    }

    /**
     * @covers Peach_Util_Strings::getLines
     */
    public function testGetLines()
    {
        $str = "This\ris\na\r\npen.";
        $exp = array("This", "is", "a", "pen.");
        $this->assertSame($exp, Peach_Util_Strings::getLines($str));
    }

    /**
     * @covers Peach_Util_Strings::isWhitespace
     */
    public function testIsWhitespace()
    {
        $this->assertSame(true,  Peach_Util_Strings::isWhiteSpace(""));
        $this->assertSame(true,  Peach_Util_Strings::isWhiteSpace(null));
        $this->assertSame(true,  Peach_Util_Strings::isWhiteSpace(false));
        $this->assertSame(false, Peach_Util_Strings::isWhiteSpace(true));
        $this->assertSame(false, Peach_Util_Strings::isWhiteSpace(0));
        $this->assertSame(true,  Peach_Util_Strings::isWhiteSpace("    \t\r\n    "));
        $this->assertSame(false, Peach_Util_Strings::isWhiteSpace("  asdf "));
    }

    /**
     * @covers Peach_Util_Strings::basedir
     */
    public function testBasedir()
    {
        $this->assertSame("/foo/bar/baz/", Peach_Util_Strings::basedir("/foo/bar/baz"));
        $this->assertSame("/hoge/fuga/",   Peach_Util_Strings::basedir("/hoge/fuga/"));
        $this->assertSame("",              Peach_Util_Strings::basedir(""));
        $this->assertSame("asdf/",         Peach_Util_Strings::basedir("asdf"));
        $obj = new Peach_Util_StringsTest_Object("/aaa/bbb/ccc");
        $this->assertSame("/aaa/bbb/ccc/", Peach_Util_Strings::basedir($obj));
    }

    /**
     * @covers Peach_Util_Strings::getRawIndex
     */
    public function testGetRawIndex()
    {
        $this->assertSame(3,     Peach_Util_Strings::getRawIndex("abc=def", "="));
        $this->assertSame(7,     Peach_Util_Strings::getRawIndex("a\\=b\\=c=d", "="));
        $this->assertSame(false, Peach_Util_Strings::getRawIndex("", "="));
        $this->assertSame(false, Peach_Util_Strings::getRawIndex("a\\=b", "="));
        $this->assertSame(1,     Peach_Util_Strings::getRawIndex("a=\\=b", "="));
    }

    /**
     * @covers Peach_Util_Strings::startsWith
     */
    public function testStartsWith()
    {
        $this->assertSame(true,  Peach_Util_Strings::startsWith("The quick brown fox", "The"));
        $this->assertSame(false, Peach_Util_Strings::startsWith("Hogehoge", "hoge"));
        $this->assertSame(true,  Peach_Util_Strings::startsWith("something", ""));
        $prefix  = new Peach_Util_StringsTest_Object("TEST");
        $subject = new Peach_Util_StringsTest_Object("TEST object");
        $other   = new Peach_Util_StringsTest_Object("fuga");
        $this->assertSame(true,  Peach_Util_Strings::startsWith($subject, $prefix));
        $this->assertSame(false, Peach_Util_Strings::startsWith($subject, $other));
    }

    /**
     * @covers Peach_Util_Strings::endsWith
     */
    public function testEndsWith()
    {
        $this->assertSame(true,  Peach_Util_Strings::endsWith("The quick brown fox", "fox"));
        $this->assertSame(false, Peach_Util_Strings::endsWith("Hogehoge", "Hoge"));
        $this->assertSame(true,  Peach_Util_Strings::endsWith("something", ""));
        $suffix  = new Peach_Util_StringsTest_Object("TEST");
        $subject = new Peach_Util_StringsTest_Object("objectTEST");
        $other   = new Peach_Util_StringsTest_Object("fuga");
        $this->assertSame(true,  Peach_Util_Strings::endsWith($subject, $suffix));
        $this->assertSame(false, Peach_Util_Strings::endsWith($subject, $other));
    }

    /**
     * @covers Peach_Util_Strings::endsWithRawChar
     */
    public function testEndsWithRawChar()
    {
        $this->assertSame(true,  Peach_Util_Strings::endsWithRawChar("ABCDE",       "DE"));
        $this->assertSame(true,  Peach_Util_Strings::endsWithRawChar("AB\\CDE",     "DE"));
        $this->assertSame(false, Peach_Util_Strings::endsWithRawChar("ABC\\DE",     "DE"));
        $this->assertSame(true,  Peach_Util_Strings::endsWithRawChar("ABC\\\\DE",   "DE"));
        $this->assertSame(false, Peach_Util_Strings::endsWithRawChar("ABC\\\\\\DE", "DE"));
    }

    /**
     * @covers Peach_Util_Strings::template
     * @todo   Implement testTemplate().
     */
    public function testTemplate()
    {
        $exp1  = "hoge";
        $test1 = "hoge";
        $arr1  = array();
        $this->assertSame($exp1, Peach_Util_Strings::template($test1, $arr1));
        
        $exp2  = "I am Tom, 12 years old.";
        $test2 = "I am {0}, {1} years old.";
        $arr2  = array("Tom", 12);
        $this->assertSame($exp2, Peach_Util_Strings::template($test2, $arr2));
        
        $exp3  = "I am {1}.";
        $test3 = "I am {1}.";
        $arr3  = array("John");
        $this->assertSame($exp3, Peach_Util_Strings::template($test3, $arr3));
        
        $exp4  = "First:{1},Second:{0}";
        $test4 = "First:{0},Second:{1}";
        $arr4  = array("{1}", "{0}");
        $this->assertSame($exp4, Peach_Util_Strings::template($test4, $arr4));
        
        $exp5  = "I am John, 20 years old.";
        $test5 = "I am {name}, {age} years old.";
        $arr5 = array("name" => "John", "age" => 20);
        $this->assertSame($exp5, Peach_Util_Strings::template($test5, $arr5));
    }
}

class Peach_Util_StringsTest_Object
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }
    
    public function __toString()
    {
        return $this->value;
    }
}
?>