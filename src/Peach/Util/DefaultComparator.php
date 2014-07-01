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
/** @package Util */
/**
 * デフォルトのコンパレータです.
 * {@link Peach_Util_Arrays::sort()} などのメソッドで, 
 * 引数にコンパレータが指定されなかった場合に適用されます.
 * 
 * @package Util
 */
class Peach_Util_DefaultComparator implements Peach_Util_Comparator
{
    /**
     * 二つの値を比較します.
     * $var1 === $var2 の場合は 0 を返します.
     * 
     * @param mixed $var1
     * @param mixed $var2
     */
    public function compare($var1, $var2)
    {
        if ($var1 === $var2) {
            return 0;
        }
        if (is_object($var1) || is_object($var2)) {
            return self::compareObjects($var1, $var2);
        }
        if (is_array($var1) || is_array($var2)) {
            return self::compareArrays($var1, $var2);
        }
        if (is_string($var1) || is_string($var2)) {
            return strcmp($var1, $var2);
        }
        if ($var1 == $var2) {
            return self::compareTypes($var1, $var2);
        }
        
        return ($var1 > $var2) ? 1 : -1;
    }
    
    /**
     * var_dump の出力から, 冒頭の object(#x) 部分を除いた文字列を返します.
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
        
        $classNamePattern = "([a-zA-Z_\\x7f-\\xff][a-zA-Z0-9\\\\_\\x7f-\\xff]*)";
        if (preg_match("/^object\\({$classNamePattern}\\)#(\\d+)/", $data)) {
            return preg_replace("/^object\\({$classNamePattern}\\)#(\\d+)/", "$1", $data);
        }
        if (preg_match("/^class {$classNamePattern}#(\\d+)/", $data)) {
            return preg_replace("/^class {$classNamePattern}#(\\d+)/", "$1", $data);
        }
        return $data;
    }
    
    /**
     * 引数のオブジェクトを比較します.
     * 引数のうち, 少なくとも一方が {@link Peach_Util_Comparable} を実装していた場合,
     * そのオブジェクトの compareTo の結果を返します.
     * それ以外の場合は var_dump の結果を文字列比較して大小を判定します.
     * 
     * @param mixed $var1
     * @param mixed $var2
     * @return type 
     */
    private static function compareObjects($var1, $var2)
    {
        if ($var1 instanceof Peach_Util_Comparable) {
            return $var1->compareTo($var2);
        }
        if ($var2 instanceof Peach_Util_Comparable) {
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
        }
        
        return ($var1 > $var2) ? 1 : -1;
    }
    
    /**
     * 異なる値 $var1, $var2 について, 
     * 一方が NULL や FALSE などの値だった場合の大小関係を定義します.
     * この実装は NULL < FALSE < array() という順序付けをします.
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return int   比較結果が等しい場合は 0, $var1 > $var2 の場合は 1, それ以外は -1
     */
    private static function compareTypes($var1, $var2)
    {
        if ($var1 === null) {
            return -1;
        }
        if ($var2 === null) {
            return 1;
        }
        if (is_bool($var1)) {
            return -1;
        }
        if (is_bool($var2)) {
            return 1;
        }
        if ($var1 === array()) {
            return -1;
        }
        if ($var2 === array()) {
            return 1;
        }
        
        return 0;
    }
    
    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}
    
    /**
     * 唯一のインスタンスを返します.
     * 
     * @return Peach_Util_DefaultComparator
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
}
