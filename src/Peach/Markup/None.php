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
 * 「何もない」ことをあらわす Component です. (Null Object パターン)
 *
 * この Component は以下の特徴を持ちます.
 * 
 * - コンテナに append しても何も追加されない
 * - Context に処理させても何も行われない (ただし DebugContext を除く)
 * 
 * @package Markup
 */
class Peach_Markup_None implements Peach_Markup_Component
{
    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}
    
    /**
     * このクラスの唯一のインスタンスを返します.
     * @return Peach_Markup_None
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!isset($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
    
    /**
     * 指定された Context にこのノードを処理させます.
     * {@link Peach_Markup_Context::handleNone()} を呼び出します.
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleNone($this);
    }
}
