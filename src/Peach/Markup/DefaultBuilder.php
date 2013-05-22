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
class Peach_Markup_DefaultBuilder extends Peach_Markup_Builder {
    /**
     * 
     * @var Peach_Markup_Indent
     */
    private $indent;
    
    /**
     * 
     * @var Peach_Markup_Renderer
     */
    private $renderer;
    
    /**
     * 
     * @var Peach_Markup_BreakControl
     */
    private $breakControl;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->indent        = null;
        $this->renderer      = null;
        $this->breakControl  = null;
    }
    
    /**
     * @param  Peach_Markup_Indent $indent
     */
    public function setIndent(Peach_Markup_Indent $indent)
    {
        $this->indent = $indent;
    }
    
    /**
     * 
     * @param  Peach_Markup_Renderer $renderer
     */
    public function setRenderer(Peach_Markup_Renderer $renderer)
    {
        $this->renderer = $renderer;
    }
    
    /**
     * 
     * @param Peach_Markup_BreakControl $breakControl
     */
    public function setBreakControl(Peach_Markup_BreakControl $breakControl) {
        $this->breakControl = $breakControl;
    }
    
    /**
     * @return Markup_Context
     */
    protected function createContext()
    {
        $indent        = isset($this->indent)        ? clone($this->indent) : new Peach_Markup_Indent();
        $renderer      = isset($this->renderer)      ? $this->renderer      : Peach_Markup_XmlRenderer::getInstance();
        $breakControl  = isset($this->breakControl)  ? $this->breakControl  : Peach_Markup_DefaultBreakControl::getInstance();
        return new Peach_Markup_DefaultContext($renderer, $indent, $breakControl);
    }
}
?>