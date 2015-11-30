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
 * 空白文字をあらわす Expression です. 以下の BNF ルールを解釈します.
 * 
 * <pre>
 * ws = *(
 *         %x20 /              ; Space
 *         %x09 /              ; Horizontal tab
 *         %x0A /              ; Line feed or New line
 *         %x0D )              ; Carriage return
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_WS implements Peach_DF_JsonCodec_Expression
{
    /**
     * このクラスはシングルトンです.
     */
    private function __construct() {}
    
    /**
     * このクラスの唯一のインスタンスを返します.
     * 
     * @return Peach_DF_JsonCodec_WS
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * 文字 %x20, %x09, %x0A, %x0D を読み飛ばします.
     * 
     * @param Peach_DF_JsonCodec_Context $context
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        static $wsList = array("\r", "\n", "\r\n", "\t", " ");
        while ($context->hasNext()) {
            $current = $context->current();
            if (!in_array($current, $wsList)) {
                break;
            }
            $context->next();
        }
    }
}
