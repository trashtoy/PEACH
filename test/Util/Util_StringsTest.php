<?php

require_once 'Util/load.php';

class Util_StringsTest extends PHPUnit_Framework_TestCase {
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
    
    public function testExplode() {
        $this->assertSame(array("A","B","C"), Util_Strings::explode("-", "A-B-C"));
        $this->assertSame(array(), Util_Strings::explode("", "A-B-C"));
        $obj = new Util_StringsTest_Object("A-B-C");
        $this->assertSame(array("A","B","C"), Util_Strings::explode("-", $obj));
    }
    
    public function testGetLines() {
        $str = "This\ris\na\r\npen.";
        $exp = array("This", "is", "a", "pen.");
        $this->assertSame($exp, Util_Strings::getLines($str));
    }
    
    public function testIsWhiteSpace() {
        $this->assertSame(TRUE,  Util_Strings::isWhiteSpace(""));
        $this->assertSame(TRUE,  Util_Strings::isWhiteSpace(NULL));
        $this->assertSame(TRUE,  Util_Strings::isWhiteSpace(FALSE));
        $this->assertSame(FALSE, Util_Strings::isWhiteSpace(TRUE));
        $this->assertSame(FALSE, Util_Strings::isWhiteSpace(0));
        $this->assertSame(TRUE,  Util_Strings::isWhiteSpace("    \t\r\n    "));
        $this->assertSame(FALSE, Util_Strings::isWhiteSpace("  asdf "));
    }
    
    public function testBasedir() {
        $this->assertSame("/foo/bar/baz/", Util_Strings::basedir("/foo/bar/baz"));
        $this->assertSame("/hoge/fuga/",   Util_Strings::basedir("/hoge/fuga/"));
        $this->assertSame("",              Util_Strings::basedir(""));
        $this->assertSame("asdf/",         Util_Strings::basedir("asdf"));
        $obj = new Util_StringsTest_Object("/aaa/bbb/ccc");
        $this->assertSame("/aaa/bbb/ccc/", Util_Strings::basedir($obj));
    }
    
    public function testGetRawIndex() {
        $this->assertSame(3,     Util_Strings::getRawIndex("abc=def", "="));
        $this->assertSame(7,     Util_Strings::getRawIndex("a\\=b\\=c=d", "="));
        $this->assertSame(FALSE, Util_Strings::getRawIndex("", "="));
        $this->assertSame(FALSE, Util_Strings::getRawIndex("a\\=b", "="));
        $this->assertSame(1,     Util_Strings::getRawIndex("a=\\=b", "="));
    }
    
    public function testStartsWith() {
        $this->assertSame(TRUE,  Util_Strings::startsWith("The quick brown fox", "The"));
        $this->assertSame(FALSE, Util_Strings::startsWith("Hogehoge", "hoge"));
        $this->assertSame(TRUE,  Util_Strings::startsWith("something", ""));
        $prefix  = new Util_StringsTest_Object("TEST");
        $subject = new Util_StringsTest_Object("TEST object");
        $other   = new Util_StringsTest_Object("fuga");
        $this->assertSame(TRUE,  Util_Strings::startsWith($subject, $prefix));
        $this->assertSame(FALSE, Util_Strings::startsWith($subject, $other));
    }
    
    public function testEndsWith() {
        $this->assertSame(TRUE,  Util_Strings::endsWith("The quick brown fox", "fox"));
        $this->assertSame(FALSE, Util_Strings::endsWith("Hogehoge", "Hoge"));
        $this->assertSame(TRUE,  Util_Strings::endsWith("something", ""));
        $suffix  = new Util_StringsTest_Object("TEST");
        $subject = new Util_StringsTest_Object("objectTEST");
        $other   = new Util_StringsTest_Object("fuga");
        $this->assertSame(TRUE,  Util_Strings::endsWith($subject, $suffix));
        $this->assertSame(FALSE, Util_Strings::endsWith($subject, $other));
    }
    
    public function testEndsWithRawChar() {
        $this->assertSame(TRUE,  Util_Strings::endsWithRawChar("ABCDE",       "DE"));
        $this->assertSame(TRUE,  Util_Strings::endsWithRawChar("AB\\CDE",     "DE"));
        $this->assertSame(FALSE, Util_Strings::endsWithRawChar("ABC\\DE",     "DE"));
        $this->assertSame(TRUE,  Util_Strings::endsWithRawChar("ABC\\\\DE",   "DE"));
        $this->assertSame(FALSE, Util_Strings::endsWithRawChar("ABC\\\\\\DE", "DE"));
    }
    
    public function testTemplate() {
        $exp  = "hoge";
        $test = "hoge";
        $arr  = array();
        $this->assertSame($exp, Util_Strings::template($test, $arr));
        
        $exp  = "I am Tom, 12 years old.";
        $test = "I am {0}, {1} years old.";
        $arr  = array("Tom", 12);
        $this->assertSame($exp, Util_Strings::template($test, $arr));
        
        $exp  = "I am {1}.";
        $test = "I am {1}.";
        $arr  = array("John");
        $this->assertSame($exp, Util_Strings::template($test, $arr));
        
        $exp  = "First:{1},Second:{0}";
        $test = "First:{0},Second:{1}";
        $arr  = array("{1}", "{0}");
        $this->assertSame($exp, Util_Strings::template($test, $arr));
    }
}

class Util_StringsTest_Object {
    private $value;
    
    public function __construct($value) {
        $this->value = $value;
    }
    
    public function __toString() {
        return $this->value;
    }
}
?>