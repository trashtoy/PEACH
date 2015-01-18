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
 * SGML や XML をマークアップするための必要最低限の書式化ルールを定義した抽象基底クラスです.
 * 
 * {@link Peach_Markup_SgmlRenderer} および {@link Peach_Markup_XmlRenderer}
 * の共通部分の実装です.
 *
 * @package Markup
 */
abstract class Peach_Markup_AbstractRenderer implements Peach_Markup_Renderer
{
    /**
     * 開始タグをマークアップします.
     * この書式化ルールは SGML, XML 共通です.
     * @param  Peach_Markup_Element $element 要素
     * @return string
     */
    public final function formatStartTag(Peach_Markup_Element $element)
    {
        return $this->formatTagPrefix($element) . ">";
    }
    
    /**
     * 終了タグをマークアップします.
     * この書式化ルールは SGML, XML 共通です.
     * @param  Peach_Markup_Element $element 要素
     * @return string
     */
    public final function formatEndTag(Peach_Markup_Element $element)
    {
        return "</" . $element->getName() . ">";
    }
    
    /**
     * 空要素タグをマークアップします.
     * タグの末尾の書式化方法は各サブクラスに依存します.
     * @param  Peach_Markup_Element $element 要素
     * @return string
     */
    public final function formatEmptyTag(Peach_Markup_Element $element)
    {
        return $this->formatTagPrefix($element) . $this->formatEmptyTagSuffix();
    }
    
    /**
     * 開始タグまたは空要素タグの共通部分を書式化します.
     * @param  Peach_Markup_Element $element 書式化対象の要素
     * @return string               "<elementName ... "
     */
    protected final function formatTagPrefix(Peach_Markup_Element $element)
    {
        $tag  = "<";
        $tag .= $element->getName();
        foreach ($element->getAttributes() as $name => $value) {
            $tag .= " ";
            $tag .= ($value === null) ? 
                $this->formatBooleanAttribute($name) : $this->formatAttribute($name, $value);
        }
        return $tag;
    }
    
    /**
     * 指定された属性を書式化します.
     * 
     * @param  string $name  属性名
     * @param  string $value 属性値
     * @return string        属性
     */
    protected abstract function formatAttribute($name, $value);
    
    /**
     * 値の省略された属性を書式化します.
     * 
     * @param  string $name 属性名
     * @return string       属性
     */
    protected abstract function formatBooleanAttribute($name);
    
    /**
     * 空要素タグの末尾を書式化します.
     * @return string 空要素の末尾. SGML は ">", XML は " />".
     */
    protected abstract function formatEmptyTagSuffix();
}
