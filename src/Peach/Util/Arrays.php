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
 * 配列に関する操作を行うユーティリティクラスです.
 * @package Util
 */
class Peach_Util_Arrays
{
    /**
     * このクラスはインスタンス化できません
     */
    private function __construct() {}

    /**
     * {@link Peach_Util_Arrays::max()} と {@link Peach_Util_Arrays::min()} の共通部分の実装です.
     * @param  array                 $arr           
     * @param  Peach_Util_Comparator $c
     * @param  bool                  $isMax
     * @return mixed
     */
    private static function getTop(array $arr, Peach_Util_Comparator $c = null, $isMax = false)
    {
        if (!isset($c)) {
            $c = Peach_Util_DefaultComparator::getInstance();
        }
        $candidate = null;
        foreach ($arr as $current) {
            if (!isset($candidate)) {
                $candidate = $current;
                continue;
            }
            $comp = $c->compare($current, $candidate);
            if (($isMax && 0 < $comp) || (!$isMax && $comp < 0)) {
                $candidate = $current;
            }
        }
        return $candidate;
    }
    
    /**
     * 指定された配列の各要素の中で「最も大きい」値を返します.
     * 
     * 配列に含まれるオブジェクトが {@link Peach_Util_Comparable} を実装している場合は
     * そのオブジェクトの {@link Peach_Util_Comparable::compareTo() compareTo()} 
     * メソッドを使って大小の比較を行います. それ以外の値の場合は
     * {@link http://jp.php.net/manual/ja/language.operators.comparison.php 
     * PHP の比較演算子のルール} に従って大小比較が行われます.
     * 
     * 大小の比較方法が型によって異なるため, 
     * 配列内の要素の型に一貫性がない場合は, 格納順序の違いによって
     * 異なる結果となる可能性があります. 
     * 意図しない動作を防ぐためには, あらかじめ {@link Peach_Util_Arrays::pickup()} 
     * を使って配列に含まれる型を揃えてください.
     * 
     * オプションとして第 2 引数に {@link Peach_Util_Comparator} 
     * オブジェクトを指定することもできます.
     * もし第 2 引数が指定された場合は {@link Peach_Util_Comparator::compare() compare()} 
     * メソッドを使って大小比較を行います.
     * 
     * @param  array                 $arr
     * @param  Peach_Util_Comparator $c
     * @return mixed                 引数 $arr の中で最も大きな値. 配列が空の場合は NULL
     */
    public static function max(array $arr, Peach_Util_Comparator $c = null)
    {
        return self::getTop($arr, $c, true);
    }

    /**
     * 指定された配列の各要素の中で「最も小さい」値を返します.
     * 
     * 配列に含まれるオブジェクトが {@link Peach_Util_Comparable} を実装している場合は
     * そのオブジェクトの {@link Peach_Util_Comparable::compareTo() compareTo()} 
     * メソッドを使って大小の比較を行います. それ以外の値の場合は
     * {@link http://jp.php.net/manual/ja/language.operators.comparison.php 
     * PHP の比較演算子のルール} に従って大小比較が行われます.
     * 
     * 大小の比較方法が型によって異なるため, 
     * 配列内の要素の型に一貫性がない場合は, 格納順序の違いによって
     * 異なる結果となる可能性があります. 
     * 意図しない動作を防ぐためには, あらかじめ {@link Peach_Util_Arrays::pickup()} 
     * を使って配列に含まれる型を揃えてください.
     * 
     * オプションとして第 2 引数に {@link Peach_Util_Comparator} 
     * オブジェクトを指定することもできます.
     * もし第 2 引数が指定された場合は {@link Peach_Util_Comparator::compare() compare()} 
     * メソッドを使って大小比較を行います.
     * 
     * @param  array                 $arr
     * @param  Peach_Util_Comparator $c
     * @return mixed $arr の中で最も大きな値. 配列が空の場合は NULL
     */
    public static function min(array $arr, Peach_Util_Comparator $c = null)
    {
        return self::getTop($arr, $c, false);
    }

    /**
     * 配列の中から, $type で指定した型の値だけを取り出します. 
     * $keyFlag に TRUE を指定した場合はキーと要素の関連付けを維持します.
     * $type に指定できる文字列は以下のとおりです. (大文字・小文字は区別しません)
     * 
     * - int
     * - integer
     * - numeric
     * - float (※ numeric と同じです. int 型の値もマッチングします.)
     * - string
     * - bool
     * - boolean
     * - array
     * - object
     * - resource
     * - null
     * 
     * 上記に挙げた以外の文字列を指定した場合は, クラス (インタフェース) 名として扱います.
     * 
     * @param  array  $arr     対象の配列
     * @param  string $type    'int', 'integer', 'numeric', 'float', 'string', 'bool', 
     *                         'object', 'resource' など. 
     *                         それ以外の文字列はクラス (インタフェース) 名として扱う
     * @param  bool   $keyFlag 関連付けを維持する場合は TRUE (デフォルトは FALSE)
     * @return array
     */
    public static function pickup(array $arr, $type, $keyFlag = false)
    {
        $result = array();
        foreach ($arr as $key => $value) {
            if (self::pickupMatch($value, $type)) {
                if ($keyFlag) {
                    $result[$key] = $value;
                } else {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * 指定された値が第 2 引数で指定した型にマッチするかどうかを調べます.
     * 
     * @param  string  $value 検査対象の値
     * @param  string  $type  型
     * @return bool           引数 $value の型が $type に合致する場合に true
     */
    private static function pickupMatch($value, $type)
    {
        $ltype = strtolower($type);
        switch ($ltype) {
            case 'int':
            case 'integer':
                return is_int($value);
            case 'float':
            case 'numeric':
                return is_numeric($value);
            case 'string':
                return is_string($value);
            case 'null':
                return is_null($value);
            case 'bool':
            case 'boolean':
                return is_bool($value);
            case 'array':
                return is_array($value);
            case 'resource':
                return is_resource($value);
            case 'object':
                return is_object($value);
            default:
                return is_object($value) && ($value instanceof $type);
        }
    }

    /**
     * 指定された配列をソートします.
     * 配列のキーは連番で初期化されます.
     * コンパレータが指定されなかった場合は {@link Peach_Util_DefaultComparator} が適用されます.
     * 
     * @param  array                 $arr ソート対象の配列
     * @param  Peach_Util_Comparator $c   コンパレータ
     * @return array
     */
    public static function sort(array $arr, Peach_Util_Comparator $c = null)
    {
        if (count($arr) < 2) {
            return $arr;
        }
        if (!isset($c)) {
            $c = Peach_Util_DefaultComparator::getInstance();
        }
        usort($arr, array($c, "compare"));
        return $arr;
    }
    
    /**
     * 配列のキーと値のマッピングを保持しながら, 指定された配列をソートします.
     * コンパレータが指定されなかった場合は {@link Util_DefaultComparator} が適用されます.
     * 
     * @param  array                 $arr ソート対象の配列
     * @param  Peach_Util_Comparator $c   コンパレータ
     * @return array                      ソート後の配列
     */
    public static function asort(array $arr, Peach_Util_Comparator $c = null)
    {
        if (count($arr) < 2) {
            return $arr;
        }
        if (!isset($c)) {
            $c = Peach_Util_DefaultComparator::getInstance();
        }
        uasort($arr, array($c, "compare"));
        return $arr;
    }
    
    /**
     * 引数の配列または値を連結して, 一つの配列として返します.
     * 
     * 例えば
     * 
     * <code>
     * $arr1   = array(10, 20, 30);
     * $arr2   = array(40, 50);
     * $concat = Peach_Util_Arrays::concat($arr1, "X", $arr2, "Y");
     * </code>
     * 
     * の結果は
     * <code>array(10, 20, 30, "X", 40, 50, "Y")</code>
     * に等しくなります。
     * 
     * この関数は多次元配列はサポートしません.
     * 
     * <code>
     * $arr1 = array("hoge", "fuga");
     * $arr2 = array(
     *     array(1, 2, 3),
     *     array(5, 7)
     * );
     * $concat = Peach_Util_Arrays::concat($arr1, $arr2);
     * </code> 
     * 
     * の結果は
     * 
     * <code>
     * array(
     *     "hoge",
     *     "fuga",
     *     array(1, 2, 3), 
     *     array(5, 7)
     * )
     * </code>
     * 
     * となります.
     * 
     * この関数は, 配列に含まれるキーをすべて無視します.
     * 結果の配列のキーは連番で初期化されます.
     * 
     * @return array
     */
    public static function concat()
    {
        $args = func_get_args();
        $result = array();
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach ($arg as $subarg) {
                    $result[] = $subarg;
                }
            } else {
                $result[] = $arg;
            }
        }
        return $result;
    }

    /**
     * 指定された配列から, 重複した値を取り除いた結果を返します.
     * 重複かどうかの判定は, 引数に指定されたコンパレータを使って行われます.
     * コンパレータが指定されなかった場合は {@link Peach_Util_DefaultComparator} が適用されます.
     * 
     * @param  array                 $arr
     * @param  Peach_Util_Comparator $c   コンパレータ
     * @return array 
     */
    public static function unique(array $arr, Peach_Util_Comparator $c = null)
    {
        if (!isset($c)) {
            $c = Peach_Util_DefaultComparator::getInstance();
        }
        $sorted = self::asort($arr, $c);
        $delKey = array();
        list($lastKey, $lastValue) = each($sorted);
        while (list($key, $value) = each($sorted)) {
            if ($c->compare($value, $lastValue) === 0 && $value == $lastValue) {
                $delKey[] = $key;
                continue;
            } else {
                $lastKey = $key;
                $lastValue = $value;
            }
        }
        foreach ($delKey as $key) {
            unset($arr[$key]);
        }
        return $arr;
    }
}
