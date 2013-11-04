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
 * @package Markup
 */
class Peach_Markup_Helper
{
    /**
     *
     * @var Peach_Markup_Builder
     */
    private $builder;
    
    /**
     *
     * @var array
     */
    private $emptyNodeNames;
    
    public function __construct(Peach_Markup_Builder $builder, array $emptyNodeNames = array())
    {
        $this->builder        = $builder;
        $this->emptyNodeNames = $emptyNodeNames;
    }
    
    public function createObject($var, $attr = array())
    {
        $object = new Peach_Markup_HelperObject($this, $var);
        if (count($attr)) {
            $object->attr($attr);
        }
        return $object;
    }
    
    public function createNode($var)
    {
        if ($var instanceof Peach_Markup_Node) {
            return $var;
        }
        if ($var instanceof Peach_Markup_HelperObject) {
            return $var->getNode();
        }
        $nodeName = Peach_Util_Values::stringValue($var);
        return strlen($nodeName) ? $this->createElement($nodeName) : new Peach_Markup_NodeList();
    }
    
    public function write(Peach_Markup_HelperObject $object)
    {
        return $this->builder->build($object->getNode());
    }
    
    /**
     * 
     * @return Peach_Markup_Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }
    
    /**
     * 
     * @param Peach_Markup_Builder $builder
     */
    public function setBuilder(Peach_Markup_Builder $builder)
    {
        $this->builder = $builder;
    }
    
    /**
     *  @param string
     */
    private function createElement($name)
    {
        return in_array($name, $this->emptyNodeNames) ?
            new Peach_Markup_EmptyElement($name) : new Peach_Markup_ContainerElement($name);
    }
}
