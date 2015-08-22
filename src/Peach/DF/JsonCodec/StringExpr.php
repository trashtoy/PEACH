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
/**
 * @package DF
 * @ignore
 */
/**
 * JSON の BNF ルール string をあらわす Expression です.
 * RFC 7159 で定義されている以下のフォーマットを解釈します.
 * 
 * <pre>
 * string = quotation-mark *char quotation-mark
 * 
 *       char = unescaped /
 *           escape (
 *               %x22 /          ; "    quotation mark  U+0022
 *               %x5C /          ; \    reverse solidus U+005C
 *               %x2F /          ; /    solidus         U+002F
 *               %x62 /          ; b    backspace       U+0008
 *               %x66 /          ; f    form feed       U+000C
 *               %x6E /          ; n    line feed       U+000A
 *               %x72 /          ; r    carriage return U+000D
 *               %x74 /          ; t    tab             U+0009
 *               %x75 4HEXDIG )  ; uXXXX                U+XXXX
 * 
 *       escape = %x5C              ; \
 * 
 *       quotation-mark = %x22      ; "
 * 
 *       unescaped = %x20-21 / %x23-5B / %x5D-10FFFF
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_StringExpr implements Peach_DF_JsonCodec_Expression
{
    /**
     * 変換結果の文字列です.
     *
     * @var string
     */
    private $result;
    
    /**
     * 新しい StringExpr インスタンスを生成します.
     */
    public function __construct()
    {
        $this->result = null;
    }
    
    /**
     * 二重引用符で囲まれた JSON 文字列を解析し, 対応する文字列に変換します.
     * 
     * @param Peach_DF_JsonCodec_Context $context 処理対象の Context オブジェクト
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        $quot = $context->current();
        if ($quot !== '"') {
            $context->throwException("A string must be quoted by '\"'");
        }
        $context->next();
        $value = "";
        $escaped = false;
        while ($context->hasNext()) {
            if ($escaped) {
                $value .= $this->decodeEscapedChar($context);
                $escaped = false;
                continue;
            }
            
            $this->validateCodePoint($context);
            $current = $context->current();
            $context->next();
            switch ($current) {
                case '"':
                    $this->result = $value;
                    return;
                case "\\":
                    $escaped = true;
                    break;
                default:
                    $value .= $current;
                    break;
            }
        }
        
        $context->throwException("End of quotation mark not found");
    }
    
    /**
     * 現在の文字が Unicode 符号点 %x20 以上であるかどうか検査します.
     * 不正な文字の場合は DecodeException をスローします.
     * 
     * @param  Peach_DF_JsonCodec_Context $context 解析対象の Context
     * @throws Peach_DF_JsonCodec_DecodeException  現在の文字が %x00-1F の範囲にある場合
     */
    private function validateCodePoint(Peach_DF_JsonCodec_Context $context)
    {
        $codePoint = $context->currentCodePoint();
        if (0x20 <= $codePoint) {
            return;
        }
        
        $hex = dechex($codePoint);
        $num = (0x10 <= $codePoint) ? $hex : "0" . $hex;
        $context->throwException("Unicode code point %x{$num} is not allowed for string");
    }
    
    /**
     * 解析結果の文字列を返します. 返り値は二重引用符で囲まれた文字列部分となります.
     * 
     * @return string 解析結果
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * "\" で始まる文字列を対応する文字に変換します.
     * 
     * @param  Peach_DF_JsonCodec_Context $context
     * @return string
     */
    private function decodeEscapedChar(Peach_DF_JsonCodec_Context $context)
    {
        // @codeCoverageIgnoreStart
        static $specials = null;
        if ($specials === null) {
            $specials = array("\\" => "\\", '"' => '"', "/" => "/", "b" => chr(0x8), "f" => chr(0xC), "n" => "\n", "r" => "\r", "t" => "\t");
        }
        // @codeCoverageIgnoreEnd
        
        $current = $context->current();
        if (array_key_exists($current, $specials)) {
            $context->next();
            return $specials[$current];
        }
        
        // decode \uXXXX
        if ($current !== "u") {
            $context->throwException("Invalid escape sequence ('\\{$current}')");
        }
        $context->next();
        $hex = $context->getSequence(4);
        if (!preg_match("/^[0-9A-Fa-f]{4}$/", $hex)) {
            $context->throwException("Invalid hexadecimal sequence (Expected: \\uXXXX)");
        }
        $context->skip(4);
        return $context->encodeCodepoint(hexdec($hex));
    }
}
