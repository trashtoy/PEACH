<?php
class Peach_Markup_NameValidatorTest extends PHPUnit_Framework_TestCase
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
     * validateFast() のテストです. 以下を確認します.
     * 
     * - 1 文字目が半角アルファベットまたは ":", "-" で始まる文字列のみ true を返すこと
     * - 2 文字目以降が半角アルファベット, 数字, ":", "_", ".", "-" から成る文字列のみ true を返すこと
     * 
     * @covers Peach_Markup_NameValidator::validate
     * @covers Peach_Markup_NameValidator::validateFast
     */
    public function testValidateFast()
    {
        $invalid = array("", "1h", "<img>", " p", "input\n");
        $valid   = array("h1", "img", "_foo", ":bar", "this-is.test");
        foreach ($invalid as $name) {
            $this->assertFalse(Peach_Markup_NameValidator::validate($name));
        }
        foreach ($valid as $name) {
            $this->assertTrue(Peach_Markup_NameValidator::validate($name));
        }
    }
    
    /**
     * ASCII 以外の文字を含む引数に対する validate() のテストです.
     * NameChar および NameStartChar の定義に従って与えられた UTF-8 のバリデーションが出来ることを確認します.
     * 
     * @covers Peach_Markup_NameValidator::validate
     * @covers Peach_Markup_NameValidator::validateNameStartChar
     * @covers Peach_Markup_NameValidator::validateNameChar
     * @covers Peach_Markup_NameValidator_Range::validate
     */
    public function testValidateUtf8Name()
    {
        $invalid = array(
            "",                                         // Empty string is invalid
            chr(0xc2) . chr(0xb7) . "ABC",              // MIDDLE DOT is not a NameStartChar
            "foo" . chr(0xc3) . chr(0x97) . "bar",      // MULTIPLICATION SIGN (×) is not a NameChar
            "test" . chr(0xe2) . chr(0x80) . chr(0xbb), // REFERENCE MARK (※) is not a NameChar
        );
        $valid = array(
            implode("", array_map("chr", array(0xE3, 0x83, 0x86, 0xE3, 0x82, 0xB9, 0xE3, 0x83, 0x88))), // "テスト"
            implode("", array_map("chr", array(0x41, 0xE2, 0x81, 0x80, 0x42))), // "A⁀B"
        );
        foreach ($invalid as $name) {
            $this->assertFalse(Peach_Markup_NameValidator::validate($name));
        }
        foreach ($valid as $name) {
            $this->assertTrue(Peach_Markup_NameValidator::validate($name));
        }
    }
}
