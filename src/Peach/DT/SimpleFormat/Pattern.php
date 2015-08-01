<?php
/*
 * Copyright (c) 2015 @trashtoy
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
/** @package DT */
/**
 * Peach_DT_SimpleFormat クラスで parse メソッドを実行した際に使用されます.
 * 
 * @ignore
 */
interface Peach_DT_SimpleFormat_Pattern
{
    /**
     * 指定された文字列の先頭が, このパターンにマッチしているかどうか調べます.
     * マッチした文字列を返します.
     * もしも引数の文字列がこのパターンマッチしていない場合は null を返します.
     * 
     * @param  string $input
     * @return string
     * @throws InvalidArgumentException
     */
    public function match($input);
    
    /**
     * マッチした値を変換結果に適用します.
     * 
     * @param Peach_Util_ArrayMap $fields  変換結果
     * @param string              $matched マッチした文字列
     */
    public function apply(Peach_Util_ArrayMap $fields, $matched);
}
