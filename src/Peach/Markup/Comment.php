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
     *
     * @var string
     */
    private $prefix;
    
    /**
     *
     * @var string
     */
    private $suffix;
    
    public function __construct($prefix = "", $suffix = "")
    {
        $this->nodeList = new Peach_Markup_NodeList(null, $this);
        $this->prefix   = $prefix;
        $this->suffix   = $suffix;
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
     * 
     * @param string $text
     */
    public function appendText($text)
    {
        $this->appendNode(new Peach_Markup_Text($text));
    }
    
    /**
     * 
     * @return array
     */
    public function getChildNodes()
    {
        return $this->nodeList->getChildNodes();
    }
}
?>