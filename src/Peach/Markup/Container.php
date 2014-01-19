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
 * ノードを内部に含めることが出来るインタフェースです.
 * このインタフェースを実装したクラスに
 * {@link Peach_Markup_NodeList NodeList} や
 * {@link Peach_Markup_ContainerElement ContainerElement} などがあります.
 * 
 * @package Markup
 */
interface Peach_Markup_Container extends Peach_Markup_Component
{
    /**
     * このコンテナにノードを追加します.
     * 
     * このメソッドは, 引数の種類によって以下の挙動を取ります.
     * 引数がノードの場合は, 引数をそのまま NodeList の末尾に追加します.
     * 
     * 引数が Container の場合は, その Container に含まれる各ノードを追加します.
     * (Container 自身は追加されません)
     * 
     * 引数が配列の場合は, 配列に含まれる各ノードをこの NodeList に追加します.
     * 
     * 引数がノードではない場合は, 引数の文字列表現をテキストノードとして追加します.
     * 
     * @param Peach_Markup_Node|Peach_Markup_Container|array|string $var
     */
    public function append($var);
    
    /**
     * このコンテナの子ノードの一覧を
     * {@link Peach_Markup_Node} オブジェクトの配列として返します.
     * 
     * @return array
     */
    public function getChildNodes();
}
