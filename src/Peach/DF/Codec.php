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
/** @package DF */
/**
 * 特定のアルゴリズムに従ってデータの符号化 (文字列への変換) および復号を行うインタフェースです.
 * 
 * @package DF
 */
interface Peach_DF_Codec
{
    /**
     * 指定された値を符号化し, 変換結果の文字列を返します.
     * 引数に指定できる型はこのインタフェースの実装クラスによって異なります.
     * 
     * @param  mixed  $var 変換対象の値
     * @return string      変換結果の文字列
     */
    public function encode($var);
    
    /**
     * 指定された文字列を複合し, 別のデータ形式に変換します.
     * 返り値の型はこのインタフェースの実装クラスによって異なります.
     * 
     * @param  string $text 変換元の文字列
     * @return mixed        変換結果
     */
    public function decode($text);
}
