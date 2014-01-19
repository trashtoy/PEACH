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
 * 開始タグの名前をもとに, 改行するかどうかを判定する BreakControl です.
 * @package Markup
 */
class Peach_Markup_NameBreakControl extends Peach_Markup_BreakControlWrapper
{
    /**
     * 常に改行する要素名の一覧
     * @var array
     */
    private $breakNames;
    
    /**
     * 常に改行しない要素名の一覧
     * @var array
     */
    private $noBreakNames;
    
    /**
     * 
     * @param array $breakNames   強制的に改行する要素名
     * @param array $noBreakNames 強制的に改行しない要素名
     * @param Peach_Markup_BreakControl $original
     */
    public function __construct(array $breakNames, array $noBreakNames, Peach_Markup_BreakControl $original = null)
    {
        parent::__construct($original);
        $this->breakNames   = $breakNames;
        $this->noBreakNames = $noBreakNames;
    }
    
    /**
     * 強制的に改行する (または強制的に改行しない) 要素名のリストをもとに,
     * 指定された要素を改行するかどうかを決定します.
     * 改行リスト・非改行リストの両方に含まれている要素名の場合は, 
     * 改行リストのほうが優先されます. (つまり常に改行されます)
     * 
     * 改行リスト・非改行リストのいずれにも含まれない場合は,
     * オリジナルの BreakControl の結果を返します.
     * 
     * @param  Peach_Markup_ContainerElement $node
     * @return bool
     */
    public function breaks(Peach_Markup_ContainerElement $node)
    {
        $name = $node->getName();
        if (in_array($name, $this->breakNames)) {
            return true;
        }
        if (in_array($name, $this->noBreakNames)) {
            return false;
        }
        
        return parent::breaks($node);
    }
}
