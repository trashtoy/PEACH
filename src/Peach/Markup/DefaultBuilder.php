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
 * HTML や XML などを出力する際に使う, デフォルトの Builder です.
 * このクラスは, 以下の条件をカスタマイズすることが出来ます.
 * 
 * - インデントの文字列 (半角スペース, タブ文字)
 * - 改行コードの種類 (LF, CRLF, CR)
 * - 空要素タグや boolean 属性の出力方法 (SGML, XML)
 * 
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
     * デフォルトの設定を持つ DefaultBuilder インスタンスを生成します.
     */
    public function __construct()
    {
        $this->indent        = null;
        $this->renderer      = null;
        $this->breakControl  = null;
    }
    
    /**
     * この Builder にセットされている Indent オブジェクトを返します.
     * もしも Indent オブジェクトがセットされていない場合は null を返します.
     * 
     * @return Peach_Markup_Indent Indent オブジェクト (セットされていない場合は null)
     */
    public function getIndent()
    {
        return $this->indent;
    }
    
    /**
     * この Builder に指定された Indent オブジェクトをセットします.
     * null を指定した場合は設定を解除します.
     * 
     * @param  Peach_Markup_Indent $indent
     */
    public function setIndent(Peach_Markup_Indent $indent = null)
    {
        $this->indent = $indent;
    }
    
    /**
     * この Builder にセットされている Renderer オブジェクトを返します.
     * もしも Renderer オブジェクトがセットされていない場合は null を返します.
     * 
     * @return Peach_Markup_Renderer Renderer オブジェクト (セットされていない場合は null)
     */
    public function getRenderer()
    {
    }
    
    /**
     * この Builder に指定された Renderer オブジェクトをセットします.
     * 引数によって以下のように動作します.
     * 
     * - Peach_Markup_Renderer オブジェクトを指定した場合: そのオブジェクトをセットします
     * - 文字列 "xml" または "xhtml" を指定した場合 (大小問わず) : {@link Peach_Markup_XmlRenderer} オブジェクトをセットします
     * - 文字列 "sgml" または "html" を指定した場合 (大小問わず) : {@link Peach_Markup_SgmlRenderer} オブジェクトをセットします
     * - null を指定した場合 : 現在セットされている Renderer を解除します.
     * 
     * @param  Peach_Markup_Renderer|string $renderer
     */
    public function setRenderer($renderer = null)
    {
        $this->renderer = $this->initRenderer($renderer);
    }
    
    /**
     * @param  Peach_Markup_Renderer|string $var
     * @return Peach_Markup_Renderer
     * @throws InvalidArgumentException
     */
    private function initRenderer($var)
    {
        if ($var instanceof Peach_Markup_Renderer) {
            return $var;
        }
        if ($var === null) {
            return null;
        }
        
        $type     = strtolower(Peach_Util_Values::stringValue($var));
        $xmlList  = array("xml", "xhtml");
        if (in_array($type, $xmlList)) {
            return Peach_Markup_XmlRenderer::getInstance();
        }
        $sgmlList = array("sgml", "html");
        if (in_array($type, $sgmlList)) {
            return Peach_Markup_SgmlRenderer::getInstance();
        }
        
        throw new InvalidArgumentException("Invalid type name: {$type}.");
    }
    
    /**
     * @return Peach_Markup_BreakControl
     */
    public function getBreakControl()
    {
    }
    
    /**
     * 
     * @param  Peach_Markup_BreakControl $breakControl
     */
    public function setBreakControl(Peach_Markup_BreakControl $breakControl)
    {
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
