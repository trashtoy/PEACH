<?php
/*
 * Copyright (c) 2012 @trashtoy
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
/** @package Util */
/**
 * 相互に比較出来ることをあらわすインタフェースです. 
 * 
 * このインタフェースは, {@link Util_Arrays} のソート・大小比較関連の API で使用されます.
 * Java における
 * {@link http://java.sun.com/j2se/1.5.0/ja/docs/ja/api/java/lang/Comparable.html java.lang.Comparable} 
 * と同じ用途で使われることを想定しています.
 * 
 * @package Util
 */
interface Util_Comparable {
    /**
     * このオブジェクトと引数の値を比較します.
     * 
     * このオブジェクトが $subject より小さい場合は負の整数, 
     * このオブジェクトが $subject より大きい場合は正の整数, 
     * このオブジェクトと $subject が等しい場合は 0 を返します.
     * 
     * もしも このオブジェクトが $subject と比較できない場合は
     * NULL を返すか, または任意の例外をスローします.
     * 
     * @param  mixed $subject 比較対象の値
     * @return int            比較結果をあらわす整数
     */
    public function compareTo($subject);
}
?>