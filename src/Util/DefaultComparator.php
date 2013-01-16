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
 * デフォルトのコンパレータです.
 * {@link Arrays::sort()} などのメソッドで, 
 * 引数にコンパレータが指定されなかった場合に適用されます.
 * 
 * @package Util
 */
class Util_DefaultComparator implements Util_Comparator
{
    /**
     * 二つの値を比較します.
     * $var1 === $var2 の場合は 0 を返します.
     * 
     * 
     * @param mixed $var1
     * @param mixed $var2
     */
    public function compare($var1, $var2)
    {
        if ($var1 === $var2) {
            return 0;
        } else if (is_object($var1) || is_object($var2)) {
            return self::compareObjects($var1, $var2);
        } else if (is_array($var1) || is_array($var2)) {
            return self::compareArrays($var1, $var2);
        } else if (is_string($var1) || is_string($var2)) {
            return strcmp($var1, $var2);
        } else if ($var1 == $var2) {
            return self::compareTypes($var1, $var2);
        } else {
            return ($var1 > $var2) ? 1 : -1;
        }
    }

    /**
     * var_dump の出力から冒頭の object(#x) 部分を除いた文字列を返します.
     * 
     * @param  mixed  $var
     * @return string 
     */
    private static function dump($var)
    {
        ob_start();
        var_dump($var);
        $data = ob_get_contents();
        ob_end_clean();
        return preg_replace("/^object\\((\\w+)\\)#(\\d+)/", "object($1)", $data);
    }

    /**
     * 引数を比較します.
     * 引数のうち, 少なくとも一方が {@link Util_Comparable} を実装していた場合,
     * そのオブジェクトの compareTo の結果を返します.
     * それ以外の場合は var_dump の結果を文字列比較して大小を判定します.
     * 
     * @param mixed $var1
     * @param mixed $var2
     * @return type 
     */
    private static function compareObjects($var1, $var2)
    {
        if ($var1 instanceof Util_Comparable) {
            return $var1->compareTo($var2);
        }
        if ($var2 instanceof Util_Comparable) {
            return $var2->compareTo($var1) * (-1);
        }
        $str1 = self::dump($var1);
        $str2 = self::dump($var2);
        return strcmp($str1, $str2);
    }

    /**
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return int
     */
    private static function compareArrays($var1, $var2)
    {
        if ($var1 == $var2) {
            return 0;
        } else {
            return ($var1 > $var2) ? 1 : -1;
        }
    }

    /**
     * NULL や FALSE などの値に対して大小関係を定義し,
     * その比較結果を返します.
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return init 
     */
    private static function compareTypes($var1, $var2)
    {
        if ($var1 === null) {
            return -1;
        } else if ($var2 === null) {
            return 1;
        } else if (is_bool($var1)) {
            return -1;
        } else if (is_bool($var2)) {
            return 1;
        } else if ($var1 === array()) {
            return -1;
        } else if ($var2 === array()) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}

    /**
     * 唯一のインスタンスを返します.
     * 
     * @staticvar Util_DefaultComparator $instance
     * @return    Util_DefaultComparator
     */
    public static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}
?>