<?php
/*
 * Copyright (c) 2015 @trashtoy
 * https://github.com/trashtoy/
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/** @package Markup */
/**
 * XML の仕様書で定義されている以下の EBNF に基づいて,
 * 要素名や属性名などのバリデーションを行います.
 * 
 * <pre>
 * NameStartChar       ::= ":" | [A-Z] | "_" | [a-z] | [#xC0-#xD6] |
 *                         [#xD8-#xF6] | [#xF8-#x2FF] | [#x370-#x37D] | [#x37F-#x1FFF] | [#x200C-#x200D] |
 *                         [#x2070-#x218F] | [#x2C00-#x2FEF] | [#x3001-#xD7FF] | [#xF900-#xFDCF] | [#xFDF0-#xFFFD] | [#x10000-#xEFFFF]
 * NameChar            ::= NameStartChar | "-" | "." | [0-9] | #xB7 | [#x0300-#x036F] | [#x203F-#x2040]
 * Name                ::= NameStartChar (NameChar)*
 * </pre>
 * 
 * 参考文献: {@link http://www.w3.org/TR/REC-xml/ Extensible Markup Language (XML) 1.0 (Fifth Edition)}
 * 
 * @package Markup
 */
class Peach_Markup_NameValidator
{
    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}
    
    /**
     * 指定された文字列が XML で定義されている Name のネーミングルールに合致するかどうか調べます.
     * 
     * @param  string $name 検査対象の文字列
     * @return bool         指定された文字列が Name として妥当な場合のみ true
     */
    public static function validate($name)
    {
        if (self::validateFast($name)) {
            return true;
        }
        
        $utf8Codec  = new Peach_DF_Utf8Codec();
        $codepoints = $utf8Codec->decode($name);
        if (!count($codepoints)) {
            return false;
        }
        $start = array_shift($codepoints);
        if (!self::validateNameStartChar($start)) {
            return false;
        }
        foreach ($codepoints as $c) {
            if (!self::validateNameChar($c)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 使用頻度の高い Name 文字列 (例えば HTML タグなど) について,
     * 簡易な正規表現を使って検査します.
     * 
     * @param  string $name 検査対象の文字列
     * @return bool         "h1", "img" など, ASCII 文字から成る妥当な Name 文字列の場合のみ true
     */
    private static function validateFast($name)
    {
        $firstNameCharClass = "[a-zA-Z_:]";
        $nameCharClass      = "[a-zA-Z0-9_:\\.\\-]";
        $pattern            = "/\\A{$firstNameCharClass}{$nameCharClass}*\\z/";
        return (0 < preg_match($pattern, $name));
    }
    
    /**
     * 指定された Unicode 符号点が NameStartChar の範囲に適合するかどうかを調べます.
     * 
     * @param  int $codepoint Unicode 符号点
     * @return bool           引数が NameStartChar である場合のみ true
     */
    private static function validateNameStartChar($codepoint)
    {
        // @codeCoverageIgnoreStart
        static $range = null;
        if ($range === null) {
            $first = new Peach_Markup_NameValidator_Range(0x61, 0x7A); // a-z
            $range = $first
                    ->add(0x41, 0x5A)->add(0x3A)->add(0x5F) // A-Z, ":", "_"
                    ->add(0xC0, 0xD6)->add(0xD8, 0xF6)->add(0xF8, 0x2FF)
                    ->add(0x370, 0x37D)->add(0x37F, 0x1FFF)->add(0x200C, 0x200D)
                    ->add(0x2070, 0x218F)->add(0x2C00, 0x2FEF)->add(0x3001, 0xD7FF)
                    ->add(0xF900, 0xFDCF)->add(0xFDF0, 0xFFFD)->add(0x10000, 0xEFFFF);
        }
        // @codeCoverageIgnoreEnd
        
        return $range->validate($codepoint);
    }
    
    /**
     * 指定された Unicode 符号点が NameChar の範囲に適合するかどうかを調べます.
     * 
     * @param  int $codepoint Unicode 符号点
     * @return bool           引数が NameChar である場合のみ true
     */
    private static function validateNameChar($codepoint)
    {
        // @codeCoverageIgnoreStart
        static $range = null;
        if ($range === null) {
            $first = new Peach_Markup_NameValidator_Range(0x30, 0x39); // 0-9
            $range = $first
                    ->add(0x2D, 0x2E)->add(0xB7) // "-", ".", MIDDLE DOT
                    ->add(0x0300, 0x036F)->add(0x203F, 0x2040);
        }
        // @codeCoverageIgnoreEnd
        
        return self::validateNameStartChar($codepoint) || $range->validate($codepoint);
    }
}

/**
 * 指定された Unicode 符号点が, 特定の範囲内に存在するかどうかを確認します.
 * @ignore
 */
class Peach_Markup_NameValidator_Range
{
    /**
     * 範囲の最小値です.
     * @var int
     */
    private $min;
    
    /**
     * 範囲の最大値です.
     * @var int
     */
    private $max;
    
    /**
     * 検査対象の Unicode 符号点がこのオブジェクトの
     * $min と $max の範囲内になかった場合に使用される次の Range オブジェクトです.
     * 
     * @var Peach_Markup_NameValidator_Range
     */
    private $next;
    
    /**
     * 
     * @param int $min 範囲の最小値
     * @param int $max 範囲の最大値
     * @param Peach_Markup_NameValidator_Range $next 次の Range オブジェクト (add が使用します)
     * @codeCoverageIgnore
     */
    public function __construct($min, $max, Peach_Markup_NameValidator_Range $next = null)
    {
        $this->min  = $min;
        $this->max  = ($max === null) ? $min : $max;
        $this->next = $next;
    }
    
    /**
     * このオブジェクトを次の Range オブジェクトとして設定した新しい Range オブジェクトを返します.
     * 
     * @param  int $min 範囲の最小値
     * @param  int $max 範囲の最大値 (単一の値を指定する場合は省略可)
     * @return Peach_Markup_NameValidator_Range
     * @codeCoverageIgnore
     */
    public function add($min, $max = null)
    {
        return new self($min, $max, $this);
    }
    
    /**
     * 指定された Unicode 符号点がこのオブジェクトが示す範囲内に存在するかどうか調べます.
     * 次の Range オブジェクトが設定されている場合, 再帰的に validate() を実行した結果を返します.
     * 
     * @param  int  $codepoint Unicode 符号点
     * @return bool            引数が範囲内に存在する場合のみ true
     */
    public function validate($codepoint)
    {
        return
            ($this->min <= $codepoint && $codepoint <= $this->max) ||
            ($this->next !== null && $this->next->validate($codepoint));
    }
}
