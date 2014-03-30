<?php
/*
 * Copyright (c) 2014 @trashtoy
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
 * デバッグ用の Builder です.
 * この Builder は build() 実行時に自動的にデバッグ文字列の echo を行います.
 * (自動 echo を無効化することも出来ます)
 * @package Markup
 */
class Peach_Markup_DebugBuilder extends Peach_Markup_Builder
{
    /**
     * 新しい DebugBuilder を構築します.
     * 引数で, 自動 echo を行うかどうか指定することが出来ます. (デフォルトは true です)
     * @param bool $echoMode 自動 echo を行うか
     */
    public function __construct($echoMode = true)
    {
        $this->echoMode = (bool) $echoMode;
    }
    
    /**
     * この Builder にセットされた echoMode で DebugContext を初期化します.
     * @return Peach_Markup_DebugContext
     */
    protected function createContext()
    {
        return new Peach_Markup_DebugContext($this->echoMode);
    }
}
