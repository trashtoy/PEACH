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
 * 二つの値が等価かどうかを調べるためのインタフェースです.
 * {@link Util_HashMap} などでキーの等価性をチェックするために使用されます.
 * 
 * @todo いわゆる "Latitude Zero" と区別するため, 違うインタフェース名にしたい
 * @package Util
 */
interface Util_Equator {
    /**
     * 引数 $var1 と $var2 が等しいと判断した場合に TRUE を返します.
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return bool  引数の $var1, $var2 が等しい場合に TRUE を返します.
     */
    public function equate($var1, $var2);
    
    /**
     * 指定された値のハッシュ値を計算します.
     * もしもこのオブジェクトの equate($var1, $var2) が TRUE を返す場合, 
     * hashCode($var1) と hashCode($var2) が必ず同じ値を返すようにしなければなりません.
     * 
     * この制約条件は, Java で例えると
     * {@link http://docs.oracle.com/javase/jp/6/api/java/lang/Object.html#equals(java.lang.Object) java.lang.Object#equals()}, 
     * {@link http://docs.oracle.com/javase/jp/6/api/java/lang/Object.html#hashCode() java.lang.Object#hashCode()}
     * で定義されている規約に相当します.
     * 
     * また, このメソッドは不正な値が指定された場合に任意の例外を投げる必要があります.
     * 
     * @param  mixed $var 任意の値
     * @return int        ハッシュ値
     * @throws Exception  不正な値が指定された場合
     */
    public function hashCode($var);
}
?>