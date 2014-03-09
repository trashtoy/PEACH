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
 * 内部に子ノードを含めることができる要素です.
 * 子ノードが存在しない場合は "<tag></tag>" のように書式化されます.
 * 
 * HTML の SCRIPT 要素などはこのクラスを使って表現します.
 * 
 * @package Markup
 */
class Peach_Markup_ContainerElement extends Peach_Markup_Element implements Peach_Markup_Container
{
    /**
     * この要素に登録されている子ノードの一覧です.
     * @var Peach_Markup_NodeList
     */
    private $childNodes;
    
    /**
     * 指定された要素名を持つコンテナ要素を構築します.
     * 
     * @param string 要素名
     */
    public function __construct($name)
    {
        parent::__construct($name);
        $this->childNodes = new Peach_Markup_NodeList(null, $this);
    }
    
    /**
     * この要素に子ノードを追加します.
     * 
     * @param  mixed 追加する値
     * @throws Exception 指定されたノードの中にこのノードが存在している場合
     */
    public function append($var)
    {
        $this->childNodes->append($var);
    }
    
    /**
     * すべての子ノードを配列で返します.
     * @return array Peach_Markup_Node の配列
     */
    public function getChildNodes()
    {
        return $this->childNodes->getChildNodes();
    }
    
    /**
     * 指定された Context にこのノードを処理させます.
     * {@link Peach_Markup_Context::handleContainerElement()} を呼び出します.
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleContainerElement($this);
    }
    
    /**
     * この要素が持つ子要素の個数を返します.
     * @return int 子要素の個数
     */
    public function size()
    {
        return $this->childNodes->size();
    }
}
