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
 * デフォルトの改行ルールです.
 * 
 * @package Markup
 */
class Peach_Markup_DefaultBreakControl implements Peach_Markup_BreakControl
{
    /**
     * 外部からインスタンス化できません.
     */
    private function __construct() {}
    
    /**
     * 指定された要素の開始タグの後ろに改行を付けるかどうかを決定します.
     * 条件は以下の通りです.
     * 
     * - もしも指定された要素に子要素がない場合は改行なし
     * - 子要素を一つだけ含み, それが整形済テキストの場合は改行あり
     * - 子要素を一つだけ含み, それがコンテナ要素の場合, 再帰的にチェックした結果
     * - 子要素を一つだけ含み, 上記以外のノードの場合は改行なし
     * - 子要素が二つ以上の場合は改行あり
     * 
     * @param  Peach_Markup_ContainerElement $node
     * @return bool
     */
    public function breaks(Peach_Markup_ContainerElement $node)
    {
        $size = $node->size();
        switch ($size) {
        case 0:
            return false;
        case 1:
            $childNodes = $node->getChildNodes();
            $child      = $childNodes[0];
            if ($child instanceof Peach_Markup_Code) {
                return true;
            }
            if ($child instanceof Peach_Markup_ContainerElement) {
                return $this->breaks($child);
            }
            return false;
        default:
            return true;
        }
    }
    
    /**
     * 唯一のインスタンスを取得します.
     * 
     * @return Peach_Markup_DefaultBreakControl
     */
    public static function getInstance()
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new self();
        }
        return $instance;
    }
}
