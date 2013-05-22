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
 * 要素内に含まれるテキストをあらわすノードです.
 * 
 * @package Markup
 */
class Peach_Markup_Text implements Peach_Markup_Node
{
    /**
     * テキストの内容です.
     * 
     * @var string
     */
    private $text;
    
    /**
     * 指定された内容のテキストノードを構築します.
     * @param string テキストの内容
     */
    public function __construct($text)
    {
        $this->text = $text;
    }
    
    /**
     * このテキストノードの内容を返します.
     * 
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * {@link Peach_Markup_Context::handleText()} を呼び出します.
     * @see Markup_Node#accept($node)
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleText($this);
    }
    
    public function __toString()
    {
        return $this->text;
    }
}
?>