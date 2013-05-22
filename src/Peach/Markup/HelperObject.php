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
 * @package Markup
 */
class Peach_Markup_HelperObject implements Peach_Markup_Container
{
    /**
     *
     * @var Peach_Markup_Helper
     */
    private $helper;
    
    /**
     *
     * @var Peach_Markup_Acceptable
     */
    private $node;
    
    public function __construct(Peach_Markup_Helper $helper, $var)
    {
        $this->helper = $helper;
        $this->node   = $helper->createNode($var);
    }
    
    public function getNode()
    {
        return $this->node;
    }
    
    public function append($var)
    {
        $node = $this->node;
        if ($node instanceof Peach_Markup_Container) {
            $node->append($var);
        } else {
            $className = get_class($node);
            trigger_error("This object ({$className}) cannot append elements.");
        }
        
        return $this;
    }
    
    public function appendCode($code)
    {
        if (!($code instanceof Peach_Markup_Code)) {
            return $this->appendCode(new Peach_Markup_Code($code));
        }
        
        return $this->append($code);
    }
    
    /**
     * {@link Peach_Markup_Element::setAttribute() setAttribute()}
     * および
     * {@link Peach_Markup_Element::setAttributes() setAttributes()}
     * の糖衣構文です.
     * 引数が配列の場合は setAttributes() を実行し,
     * 引数が 1 つ以上の文字列の場合は setAttribute() を実行します.
     * 
     * さらに jQuery のようなメソッドチェインを実現するため, このオブジェクト自身を返します.
     * @param  string|array|Peach_Util_ArrayMap $var セットする属性
     * @return Peach_Markup_HelperObject この要素自身
     */
    public function attr()
    {
        $node  = $this->node;
        if (!($node instanceof Peach_Markup_Element)) {
            $className = get_class($node);
            trigger_error("This object ({$className}) cannot set attributes.");
            return $this;
        }
        
        $count = func_num_args();
        if (!$count) {
            return $this;
        }
        
        $args  = func_get_args();
        $first = $args[0];
        if (($first instanceof Peach_Util_ArrayMap) || is_array($first)) {
            $node->setAttributes($first);
        } else {
            $second = (1 < $count) ? $args[1] : null;
            $node->setAttribute($first, $second);
        }
        return $this;
    }
    
    public function children()
    {
        return ($this->node instanceof Peach_Markup_Container) ?
            $this->node->getChildNodes() : new Peach_Markup_NodeList();
    }
    
    public function write()
    {
        return $this->helper->write($this);
    }
    
    public function debug()
    {
        $debug = new Peach_Markup_DebugContext();
        $debug->handle($this->node);
        return $debug->getResult();
    }
    
    public function prototype()
    {
        $this->helper->createObject($this->createPrototype());
    }
    
    private function createPrototype()
    {
        $original = $this->node;
        if ($original instanceof Peach_Markup_ContainerElement) {
            $node = new Peach_Markup_ContainerElement($original->getName());
            $node->setAttributes($original->getAttributes());
            return $node;
        }
        if ($original instanceof Peach_Markup_EmptyElement) {
            $node = new Peach_Markup_EmptyElement($original->getName());
            $node->setAttributes($original->getAttributes());
            return $node;
        }
        if ($original instanceof Peach_Markup_Text) {
            return new Peach_Markup_Text($original->getText());
        }
        if ($original instanceof Peach_Markup_NodeList) {
            $node = new Peach_Markup_NodeList();
            $node->appendNode($original);
            return $node;
        }
        
        return null;
    }
    
    /**
     * 
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $this->node->accept($context);
    }
    
    public function getChildNodes()
    {
        $node = $this->node;
        if ($node instanceof Peach_Markup_Node) {
            return array($node);
        }
        if ($node instanceof Peach_Markup_Container) {
            return $node->getChildNodes();
        }
        
        return array();
    }
}
?>