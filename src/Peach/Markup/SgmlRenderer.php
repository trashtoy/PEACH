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
     * @return Markup_SgmlTagFormatter 唯一のインスタンス.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    protected function formatCdataContents($value)
    {
        return htmlspecialchars($value);
    }
    
    /**
     * (non-PHPdoc)
     * @see Markup/Markup_TagFormatter#formatAbbrevAttribute($name)
     * @ignore
     */
    protected function formatBooleanAttribute($name)
    {
        return $name;
    }
    
    /**
     * (non-PHPdoc)
     * @see Markup_TagFormatter#formatAttribute($name, $value)
     * @ignore
     */
    protected function formatAttribute($name, $value)
    {
        return $name . '="' . htmlspecialchars($value) . '"';
    }
    
    /**
     * (non-PHPdoc)
     * @see Markup_TagFormatter#formatEmptyTagSuffix()
     * @ignore
     */
    protected function formatEmptyTagSuffix()
    {
        return ">";
    }
}
?>