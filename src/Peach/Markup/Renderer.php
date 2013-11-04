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
 * マークアップ言語の各種タグやコメント等の出力方法を定義するインタフェースです.
 * このインタフェースは, {@link Peach_Markup_DefaultBuilder} で使用するためのものです.
 * このインタフェース (またはこのインタフェースを実装した各具象クラス)
 * をユーザーが直接使用する機会はありません.
 * 
 * @package Markup
 */
interface Peach_Markup_Renderer
{
    /**
     * 開始タグをマークアップします.
     * '<tagName attr="attr">' 形式の文字列を返します.
     * 
     * @param  Peach_Markup_Element $element 出力対象の要素
     * @return string 開始タグ
     */
    public function formatStartTag(Peach_Markup_Element $element);
    
    /**
     * 終了タグをマークアップします.
     * '</tagName>' 形式の文字列を返します.
     * 
     * @param  Peach_Markup_Element $element 出力対象の要素
     * @return string 終了タグ
     */
    public function formatEndTag(Peach_Markup_Element $element);
    
    /**
     * 空要素タグをマークアップします.
     * SGML の場合は開始タグと同じ形式となります.
     * XML の場合は '<tagName />' のように, タグが '/>' で終了します.
     * 
     * @param  Peach_Markup_Element $element 出力対象の要素
     * @return string 空要素タグ
     */
    public function formatEmptyTag(Peach_Markup_Element $element);
}
