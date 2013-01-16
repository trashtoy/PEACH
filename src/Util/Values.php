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
 * 値の各種変換を行うユーティリティクラスです.
 * 
 * @package Util
 */
class Util_Values
{
    /**
     * このクラスはインスタンス化することができません.
     */
    private function __construct() {}

    /**
     * 指定された値を整数に変換します.
     * この関数は変換処理に {@link intval() intval()} を利用します.
     * 
     * 最小値が指定されている場合, 
     * 変換後の値が最小値より小さければ最小値を返します.
     * 
     * 最小値と最大値の両方が指定されている場合, 
     * 変換後の値が最大値より大きければ最大値を返します.
     * 
     * 最小値が最大値より大きい場合, 最大値の指定は無視されます.
     * 最大値のみ指定したい場合は, 最小値を NULL にしてください.
     * 
     * @param  mixed $value 変換元の値
     * @param  int   $min   最小値 (省略可). 変換後の値がこの値よりも小さい場合は, この値を返す.
     * @param  int   $max   最大値 (省略可). 変換後の値がこの値よりも大きい場合は, この値を返す.
     * @return int          引数を整数に変換した値
     */
    public static function intValue($value, $min = null, $max = null)
    {
        if (!is_int($value)) {
            $iValue = @intval($value);
            return self::intValue($iValue, $min, $max);
        } else if (isset($min) && isset($max) && $max < $min) {
            return self::intValue($value, $min);
        } else if (isset($min) && $value < $min) {
            return $min;
        } else if (isset($max) && $max < $value) {
            return $max;
        } else {
            return $value;
        }
    }

    /**
     * 指定された値を文字列型に変換します.
     * 
     * __toString() が定義されているオブジェクトの場合は __toString() の結果,
     * それ以外のオブジェクトはクラス名を返します.
     * リソース型の場合は {@link get_resource_type() get_resource_type()} にリソース ID
     * を付け足した結果を返します.
     * それ以外は string 型にキャストした結果を返します. 
     * 
     * @param  mixed  $value 変換対象の値
     * @return string        変換後の文字列
     */
    public static function stringValue($value)
    {
        if (is_object($value)) {
            if (method_exists($value, '__toString')) {
                return $value->__toString();
            } else {
                return get_class($value);
            }
        }
        if (is_resource($value)) {
            $parts = explode(" ", strval($value));
            $id = array_pop($parts);
            return get_resource_type($value) . " " . $id;
        }
        return is_string($value) ? $value : strval($value);
    }

    /**
     * 指定された値を配列に変換します.
     * 引数が配列の場合は引数をそのまま返します.
     * 配列以外の値の場合は, 第二引数によって結果が変わります.
     * 
     * - $wrap = TRUE の場合, 引数を長さ 1 の配列として返す.
     * - $wrap = FALSE の場合, 空の配列を返す.
     * 
     * デフォルトでは $wrap = FALSE となります.
     * 
     * @param  mixed $value 変換元の値.
     * @param  bool  $wrap  配列以外の値の場合に, 長さ 1 の配列に変換する場合は TRUE
     * @return array        変換後の配列
     */
    public static function arrayValue($value, $wrap = false)
    {
        if (is_array($value)) {
            return $value;
        } else {
            return $wrap ? array($value) : array();
        }
    }

    /**
     * 指定された値を bool 型に変換します.
     * この関数は "Yes", "No", "True", "False", "OK", "NG" などの文字列を
     * bool として扱うケースを想定したものです.
     * 
     * 引数が bool 型の場合は引数をそのまま返します.
     * 
     * 引数が文字列型の場合, 
     * 先頭の 1 バイトが T, Y, O のいずれかの場合は TRUE,
     * 先頭の 1 バイトが F, N のいずれかの場合は FALSE を返します.
     * (大文字・小文字は区別しません)
     * 
     * 数値は bool にキャストした結果を返します.
     * すなわち 0 以外の値は TRUE, 0 の場合は FALSE を返します.
     * 
     * 以上の条件にあてはまらない値の場合, 
     * $defaultValue が NULL または未指定の場合は引数を
     * bool 値にキャストした結果を返します.
     * $defaultValue が指定されている場合はその bool 値を返します.
     * 
     * @param  mixed $value        変換対象の値
     * @param  bool  $defaultValue デフォルトの返り値
     * @return bool                bool 値
     */
    public static function boolValue($value, $defaultValue = null)
    {
        if (is_bool($value)) {
            return $value;
        } else if (is_string($value)) {
            return self::stringToBool($value, $defaultValue);
        } else if (is_numeric($value)) {
            return (bool) $value;
        } else {
            return self::handleBoolValue($value, $defaultValue);
        }
    }

    /**
     * 文字列を bool 型にキャストします.
     * 
     */
    private static function stringToBool($value, $defaultValue = null)
    {
        static $tPrefix, $fPrefix;
        if (!isset($tPrefix)) {
            $tPrefix = array("t", "y", "o");
            $fPrefix = array("f", "n");
        }

        $prefix = strtolower(substr($value, 0, 1));
        if (in_array($prefix, $tPrefix)) {
            return true;
        } else if (in_array($prefix, $fPrefix)) {
            return false;
        } else {
            return self::handleBoolValue($value, $defaultValue);
        }
    }

    /**
     * デフォルト値に NULL 以外の値が指定されている場合はデフォルト値の bool 表現を,
     * そうでない場合は第一引数の bool 表現を返します.
     * @param bool $value
     * @param bool $defaultValue
     */
    private static function handleBoolValue($value, $defaultValue = null)
    {
        return (isset($defaultValue) ? (bool) $defaultValue : (bool) $value);
    }

    /**
     * 指定された値の型を返します.
     * 内部関数の {@link gettype() gettype()} とほぼ同じ動作をしますが,
     * 引数にオブジェクトを指定した場合に文字列 "object"
     * ではなくその値のクラス名を返すところが異なります.
     * 
     * @param mixed $var 検査対象の値
     */
    public static function getType($var)
    {
        return is_object($var) ? get_class($var) : gettype($var);
    }
}
?>