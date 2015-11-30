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
 * RFC 7159 で "six structural characters" として挙げられている以下の BNF ルールを解釈する
 * Expression です.
 * 
 * <pre>
 * begin-array     = ws %x5B ws  ; [ left square bracket
 *
 * begin-object    = ws %x7B ws  ; { left curly bracket
 *
 * end-array       = ws %x5D ws  ; ] right square bracket
 *
 * end-object      = ws %x7D ws  ; } right curly bracket
 *
 * name-separator  = ws %x3A ws  ; : colon
 *
 * value-separator = ws %x2C ws  ; , comma
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_StructuralChar implements Peach_DF_JsonCodec_Expression
{
    /**
     * 例えば array(",", "]") など
     * 
     * @var array
     */
    private $expected;
    
    /**
     * "[", "{", "]", "}", ":", "," のうちのどれかが入ります.
     * 
     * @var string
     */
    private $result;
    
    /**
     * 
     * @param array $expected
     */
    public function __construct(array $expected)
    {
        $this->expected = $expected;
        $this->result   = null;
    }
    
    /**
     * 
     * @param Peach_DF_JsonCodec_Context $context
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        $ws = Peach_DF_JsonCodec_WS::getInstance();
        $ws->handle($context);
        if (!$context->hasNext()) {
            $context->throwException("Unexpected end of JSON");
        }
        $this->handleChar($context);
        $ws->handle($context);
    }
    
    /**
     * 
     * @param  Peach_DF_JsonCodec_Context $context
     * @throws Peach_DF_JsonCodec_DecodeException 期待されている文字以外の文字を検知した場合
     */
    private function handleChar(Peach_DF_JsonCodec_Context $context)
    {
        $chr      = $context->current();
        $expected = $this->expected;
        if (in_array($chr, $expected, true)) {
            $this->result = $chr;
            $context->next();
            return;
        }
        
        $expectedList = implode(", ", array_map(array($this, "quote"), $expected));
        $context->throwException("'{$chr}' is not allowed (expected: {$expectedList})");
    }
    
    /**
     * 
     * @param  string $chr
     * @return string
     * @ignore
     * @codeCoverageIgnore
     */
    public function quote($chr)
    {
        return "'{$chr}'";
    }
    
    /**
     * 
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
}
