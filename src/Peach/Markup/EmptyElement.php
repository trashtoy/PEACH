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
 * 空要素をあらわすノードです. 
 * この要素は通常 "<tag>" もしくは "<tag />" のようにレンダリングされます.
 * HTML の IMG 要素などは, このクラスを使って表現します.
 * 
 * @package Markup
 */
class Peach_Markup_EmptyElement extends Peach_Markup_Element
{
    /**
     * 指定された Context にこのノードを処理させます. 
     * {@link Peach_Markup_Context::handleEmptyElement()} を呼び出します.
     * 
     * @param Peach_Markup_Context $context このノードを処理する Context
     * @see   Peach_Markup_Node::accept()
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleEmptyElement($this);
    }
}
