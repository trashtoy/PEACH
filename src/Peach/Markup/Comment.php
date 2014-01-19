<?php
/*
 * Copyright (c) 2012 @trashtoy
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
 * マークアップ言語のコメントをあらわすクラスです.
 * 単なるコメントとしての用途だけでなく, 任意のノードをコメントアウトすることも出来ます.
 * 
 * @package Markup
 */
class Peach_Markup_Comment implements Peach_Markup_Container, Peach_Markup_Node
{
    /**
     *
     * @var Peach_Markup_NodeList
     */
    private $nodeList;
    
    /**
     * コメントの先頭に付与される文字列です.
     * この値がセットされている場合, コメントの先頭は
     * "<!--prefix" のようにレンダリングされます.
     * 
     * @var string
     */
    private $prefix;
    
    /**
     * コメントの末尾に付与される文字列です.
     * この値がセットされている場合, コメントの末尾は
     * "suffix-->" のようにレンダリングされます.
     * 
     * @var string
     */
    private $suffix;
    
    /**
     * 指定された prefix と suffix を持つ Comment オブジェクトを構築します.
     * prefix と suffix は, 条件付きコメントの先頭 ("[if IE 6]>" など) と
     * 末尾 ("<![endif]" など) に使用されます.
     * 引数を指定しない場合は通常のコメントノードを生成します.
     * 
     * @param string $prefix 条件付きコメントの冒頭
     * @param string $suffix 条件付きコメントの末尾
     */
    public function __construct($prefix = "", $suffix = "")
    {
        $this->nodeList = new Peach_Markup_NodeList(null, $this);
        $this->prefix   = Peach_Util_Values::stringValue($prefix);
        $this->suffix   = Peach_Util_Values::stringValue($suffix);
    }
    
    /**
     * 
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    /**
     * 
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }
    
    /**
     * 
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleComment($this);
    }
    
    /**
     * 
     * @param mixed $var
     */
    public function append($var)
    {
        $this->nodeList->append($var);
    }
    
    /**
     * このコメントノードに含まれる子ノードの一覧を返します.
     * 
     * @return array
     */
    public function getChildNodes()
    {
        return $this->nodeList->getChildNodes();
    }
}
