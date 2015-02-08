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
 * デフォルトの Equator です.
 * @package Util
 */
class Peach_Util_DefaultEquator implements Peach_Util_Equator
{
    /** このクラスはインスタンス化できません. */
    private function __construct() {}

    /**
     * このクラスの唯一のインスタンスを返します.
     * 
     * @return Peach_Util_DefaultEquator
     * @codeCoverageIgnore
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
     * 引数 $var1 と $var2 が等価かどうか調べます.
     * 
     * 引数の少なくともいずれか一方がオブジェクトまたは配列の場合は
     * print_r() の結果を比較します.
     * (hashCode が print_r() の結果を使ってハッシュコードを生成するためです.)
     * 
     * 引数の少なくともいずれか一方が文字列の場合は両引数を文字列として比較します.
     * 引数が両方とも数値の場合は == で,
     * それ以外の場合は === で比較した結果を返します.
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return bool
     */
    public function equate($var1, $var2)
    {
        if ($var1 === $var2) {
            return true;
        }
        if (is_object($var1) || is_object($var2) || is_array($var1) || is_array($var2)) {
            $str1 = print_r($var1, true);
            $str2 = print_r($var2, true);
            return $str1 === $str2;
        }
        if (is_string($var1) || is_string($var2)) {
            return strcmp($var1, $var2) === 0;
        }
        if (is_numeric($var1) && is_numeric($var2)) {
            return $var1 == $var2;
        }
        
        return $var1 === $var2;
    }
    
    /**
     * 指定された値のハッシュ値を返します.
     * ハッシュ値の計算規則は以下のとおりです.
     * 
     * - bool 型にキャストした結果 FALSE となるような引数については 0
     * - 引数が数値表現の場合はその絶対値
     * - それ以外の値の場合は, 引数の文字列表現の {@link md5() md5()} ハッシュの一部
     * 
     * @param  mixed $var
     * @return int   引数のハッシュ値
     */
    public function hashCode($var)
    {
        if (empty($var)) {
            return 0;
        }
        if (is_numeric($var)) {
            return abs(intval($var));
        }
        
        $str = (is_object($var) || is_array($var)) ? print_r($var, true) : strval($var);
        return hexdec(substr(md5($str), 22));
    }
}
