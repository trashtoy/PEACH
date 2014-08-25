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
 * 与えられたノードを HTML や XML などの文字列に変換するクラスです.
 * @package Markup
 */
class Peach_Markup_DefaultContext extends Peach_Markup_Context
{
    /**
     * @var Peach_Markup_Indent
     */
    private $indent;
    
    /**
     * @var Peach_Markup_Renderer
     */
    private $renderer;
    
    /**
     * @var Peach_Markup_BreakControl
     */
    private $breakControl;
    
    /**
     * TRUE の場合はノードの前後にインデントと改行を付加します.
     * @var bool
     */
    private $isIndentMode;
    
    /**
     * TRUE の場合はコメントの内部を処理している状態とみなします.
     * コメントの内部にあるコメントは無視するようになります.
     * @var bool
     */
    private $isCommentMode;
    
    /**
     * handle() メソッド実行時の処理結果が格納されます.
     * @var string
     */
    private $result;
    
    /**
     * 指定された Renderer, Indent, BreakControl オブジェクトを使って
     * マークアップを行う DefaultContext オブジェクトを構築します.
     * 
     * @param Peach_Markup_Renderer     $renderer
     * @param Peach_Markup_Indent       $indent
     * @param Peach_Markup_BreakControl $breakControl
     */
    public function __construct(Peach_Markup_Renderer $renderer, Peach_Markup_Indent $indent = null, Peach_Markup_BreakControl $breakControl = null)
    {
        if (!isset($indent)) {
            $indent = new Peach_Markup_Indent();
        }
        if (!isset($breakControl)) {
            $breakControl = Peach_Markup_DefaultBreakControl::getInstance();
        }
        $this->renderer      = $renderer;
        $this->indent        = $indent;
        $this->breakControl  = $breakControl;
        $this->isIndentMode  = true;
        $this->isCommentMode = false;
        $this->result        = "";
    }
    
    /**
     * コメントノードを読み込みます.
     * @param Peach_Markup_Comment
     */
    public function handleComment(Peach_Markup_Comment $comment)
    {
        if ($this->isCommentMode) {
            $this->formatChildNodes($comment);
            return;
        }
        
        $this->isCommentMode = true;
        $prefix = $this->escapeEndComment($comment->getPrefix());
        $suffix = $this->escapeEndComment($comment->getSuffix());
        $this->result .= $this->indent() . "<!--{$prefix}";
        if ($this->isIndentMode) {
            if ($this->checkBreakModeInComment($comment)) {
                $breakCode = $this->breakCode();
                $this->result .= $breakCode;
                $this->formatChildNodes($comment);
                $this->result .= $breakCode;
                $this->result .= $this->indent();
            } else {
                $this->isIndentMode = false;
                $this->formatChildNodes($comment);
                $this->isIndentMode = true;
            }
        } else {
            $this->formatChildNodes($comment);
        }
        $this->result .= "{$suffix}-->";
        $this->isCommentMode = false;
    }
    
    private function checkBreakModeInComment(Peach_Markup_Comment $comment)
    {
        $nodes = $comment->getChildNodes();
        switch (count($nodes)) {
            case 0:
                return false;
            case 1:
                $node = $nodes[0];
                if ($node instanceof Peach_Markup_Comment) {
                    return $this->checkBreakModeInComment($node->getChildNodes());
                }
                
                return ($node instanceof Peach_Markup_Element);
            default:
                return true;
        }
    }
    
    /**
     * Text ノードを読み込みます.
     * @param Peach_Markup_Text $text
     */
    public function handleText(Peach_Markup_Text $text) {
        $this->result .= $this->indent() . $this->escape($text->getText());
    }
    
    /**
     * Code を読み込みます.
     * @param Peach_Markup_Code $code
     */
    public function handleCode(Peach_Markup_Code $code)
    {
        $text   = $code->getText();
        if (!strlen($text)) {
            return;
        }
        
        $lines  = Peach_Util_Strings::getLines($text);
        $indent = $this->indent();
        $this->result .= $indent;
        $this->result .= implode($this->breakCode() . $indent, $lines);
    }
    
    /**
     * EmptyElement を読み込みます.
     * @param Peach_Markup_EmptyElement
     * @see Peach_Markup_Context::handleEmptyElement()
     */
    public function handleEmptyElement(Peach_Markup_EmptyElement $node) {
        $this->result .= $this->indent() . $this->renderer->formatEmptyTag($node);
    }
    
    /**
     * ContainerElement を読み込みます.
     * @param Peach_Markup_ContainerElement
     * @see Peach_Markup_Context::handleContainerElement()
     */
    public function handleContainerElement(Peach_Markup_ContainerElement $element)
    {
        $this->result .= $this->indent() . $this->renderer->formatStartTag($element);
        if ($this->isIndentMode) {
            if ($this->breakControl->breaks($element)) {
                $this->result .= $this->indent->stepUp();
                $this->result .= $this->formatChildNodes($element);
                $this->result .= $this->breakCode();
                $this->result .= $this->indent->stepDown();
            } else {
                $this->isIndentMode = false;
                $this->formatChildNodes($element);
                $this->isIndentMode = true;
            }
        } else {
           $this->formatChildNodes($element);
        }
        $this->result .= $this->renderer->formatEndTag($element);
    }
    
    /**
     * NodeList を変換します.
     * @param Peach_Markup_NodeList $node
     */
    public function handleNodeList(Peach_Markup_NodeList $node)
    {
        $this->formatChildNodes($node);
    }
    
    /**
     * マークアップされたコードを返します.
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * 指定されたコンテナの子ノードを書式化します.
     * 各子ノードの出力結果の末尾には, 改行コードで連結されます. (インデントモードが ON の場合)
     * 末尾の子ノードの出力結果の後ろに改行コードは付きません.
     * 
     * @param Peach_Markup_Container $container
     */
    private function formatChildNodes(Peach_Markup_Container $container)
    {
        $nextBreak  = "";
        $breakCode  = $this->breakCode();
        $childNodes = $container->getChildNodes();
        foreach ($childNodes as $child) {
            $this->result .= $nextBreak;
            $this->handle($child);
            $nextBreak = $breakCode;
        }
    }
    
    /**
     * None を処理します. 何もせずに終了します.
     * 
     * @param Peach_Markup_None $none
     */
    public function handleNone(Peach_Markup_None $none)
    {
        isset($none);
    }
    
    /**
     * @return string
     */
    private function indent()
    {
        return $this->isIndentMode ? $this->indent->indent() : "";
    }
    
    /**
     * 
     * @return string
     */
    private function breakCode()
    {
        return $this->isIndentMode ? $this->indent->breakCode() : "";
    }
    
    /**
     * @param  string $text
     * @return string
     */
    private function escape($text)
    {
        return preg_replace("/\\r\\n|\\r|\\n/", "&#xa;", htmlspecialchars($text));
    }
    
    /**
     * @param  string $text
     * @return string
     */
    private function escapeEndComment($text)
    {
        return str_replace("-->", "--&gt;", $text);
    }
}
