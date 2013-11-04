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
 * 既存の BreakControl の振る舞いを拡張するためのラッパークラスです.
 * このクラスを継承し, breaks() をオーバーライドするようにしてください.
 * 
 * @package Markup
 */
class Peach_Markup_BreakControlWrapper implements Peach_Markup_BreakControl
{
    /**
     *
     * @var Peach_Markup_BreakControl
     */
    private $original;
    
    public function __construct(Peach_Markup_BreakControl $original = null)
    {
        if (!isset($original)) {
            $original = Peach_Markup_DefaultBreakControl::getInstance();
        }
        $this->original = $original;
    }
    
    /**
     * ラップ対象の BreakControl オブジェクトを返します.
     * @return Peach_Markup_BreakControl
     */
    public function getOriginal()
    {
        return $this->original;
    }
    
    /**
     * オリジナルの breaks() を呼び出します.
     * 
     * @param  Peach_Markup_ContainerElement $node
     * @return bool
     */
    public function breaks(Peach_Markup_ContainerElement $node)
    {
        return $this->original->breaks($node);
    }
}
