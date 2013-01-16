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
/** */
require_once(dirname(__FILE__) . "/Comparator.php");

/**
 * 基底の Comparator の比較結果を逆にするための Comparator です.
 * 
 * @package Util
 */
class Util_ReverseComparator implements Util_Comparator
{
    /**
     *
     * @var Util_Comparator
     */
    private $comparator;

    /**
     * 指定された比較条件を逆順にするための Comparator を構築します.
     * 引数が省略された場合は {@link Util_DefaultComparator} が使用されます.
     * 
     * @param Util_Comparator $comparator ソート条件
     */
    public function __construct(Util_Comparator $comparator = null)
    {
        $this->comparator = $comparator;
    }

    /**
     * この Comparator が内部で利用している, 基準の Comparator を返します.
     * @return Util_Comparator
     */
    public function getComparator()
    {
        return $this->comparator;
    }

    /**
     * 引数の順番を逆にして, オリジナルの Comparator の compare() メソッドを実行します.
     * 
     * @param mixed $var1 比較対象の値
     * @param mixed $var2 比較対象の値
     */
    public function compare($var1, $var2)
    {
        return $this->comparator->compare($var2, $var1);
    }
}
?>