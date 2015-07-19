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
 * SGML 形式の文書を書式化するための Formatter です.
 *
 * HTML を整形する場合はこちらを使用します.
 * 
 * @package Markup
 */
class Peach_Markup_SgmlRenderer extends Peach_Markup_AbstractRenderer
{
    /**
     * このクラスをインスタンス化することはできません.
     */
    private function __construct() {}
    
    /**
     * このクラスの唯一のインスタンスを取得します.
     * 
     * @return Peach_Markup_SgmlRenderer 唯一のインスタンス.
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
     * 値の省略された属性を書式化します.
     * この実装は, 指定された属性名をそのまま返します.
     * @param  string $name 属性名
     * @return string       引数と同じ文字列
     * @see Peach_Markup_AbstractRenderer::formatBooleanAttribute()
     */
    protected function formatBooleanAttribute($name)
    {
        return $name;
    }
    
    /**
     * 指定された属性を書式化します. name="value" 形式の文字列を返します.
     * 
     * @see Peach_Markup_AbstractRenderer::formatAttribute()
     * @param  string $name  属性名
     * @param  string $value 属性値
     * @return string name="value" 形式の文字列
     */
    protected function formatAttribute($name, $value)
    {
        return $name . '="' . htmlspecialchars($value) . '"';
    }
    
    /**
     * 空要素タグの末尾を書式化します. 文字列 ">" を返します.
     * 
     * @see Peach_Markup_AbstractRenderer::formatEmptyTagSuffix()
     * @return string 文字列 ">"
     */
    protected function formatEmptyTagSuffix()
    {
        return ">";
    }
}
