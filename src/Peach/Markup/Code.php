<?php
/*
 * Copyright (c) 2013 @trashtoy
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
 * 整形済のテキストデータをあらわすノードです.
 * 例えば以下のような箇所で使用されることを想定しています.
 * 
 * - HTML の style 要素, script 要素の中身
 * - あらかじめ文字列として出力されている HTML コード片
 * - DOCTYPE 宣言, CDATA セクションなど, このモジュールがサポートしていない XML 構文
 * 
 * @package Markup
 */
class Peach_Markup_Code implements Peach_Markup_Node
{
    /**
     *
     * @var string
     */
    private $text;
    
    /**
     * 引数の文字列を整形済テキストとする Code オブジェクトを生成します.
     * @param string $text 整形済テキスト
     */
    public function __construct($text)
    {
        $this->text = Peach_Util_Values::stringValue($text);
    }
    
    /**
     * 整形済テキストの内容を返します.
     * 
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * 指定された Context にこのノードを処理させます.
     * {@link Peach_Markup_Context::handleCode()} を呼び出します.
     * 
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleCode($this);
    }
    
    /**
     * このオブジェクトの文字列表現です.
     * {@link Peach_Markup_Code::getText() getText()} と同じ結果を返します.
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }
}
