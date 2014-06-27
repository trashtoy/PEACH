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
 * キーと値のマッピングを管理するインタフェースです. API は 
 * {@link http://docs.oracle.com/javase/jp/7/api/java/util/Map.html java.util.Map} 
 * を参考にして作られています.
 * 
 * このインタフェースを使うと, 今まで配列を使って
 * 
 * <code>$value = isset($arr[$name]) ? $arr[$name] : NULL;</code>
 * 
 * と書かなければならなかったコードが
 * 
 * <code>$value = $map->get($name);</code>
 * 
 * に簡略化できます. (※ E_NOTICE を無視する場合はこの限りではありません)
 * 
 * PHP の配列機能との違いはその他に以下のものがあります.
 * 
 * - 実装クラスによっては Java の Map のように任意のオブジェクトをキーとして利用できる. ({@link Peach_Util_HashMap} など)
 * - 値を取得する際, マッピングが存在しない場合にデフォルト値を適用できる.
 * - 配列の操作に多態性 (ポリモーフィズム) を持たせることが出来る.
 * 
 * 原則として, キーに可変オブジェクトを使わないでください.
 * キーに設定した可変オブジェクトが外部から変更された場合の動作は保証されません.
 * 
 * @package Util
 */
interface Peach_Util_Map
{
    /**
     * 指定されたキーにマッピングされている値を返します.
     * マッピングが存在しない場合は $defaultValue を返します.
     * 
     * @param  mixed $key          キー
     * @param  mixed $defaultValue マッピングが存在しない場合の代替値 
     *                             (デフォルトは NULL)
     * @return mixed               指定したキーに関連づけられた値. 
     *                             存在しない場合は引数のデフォルト値.
     */
    public function get($key, $defaultValue = null);

    /**
     * 指定されたキーと値を関連づけます.
     * @param  mixed $key   キー
     * @param  mixed $value 指定されたキーに関連づける値
     */
    public function put($key, $value);

    /**
     * 指定されたマップのマッピングすべてを登録します.
     * 
     * @param Peach_Util_Map $map 登録対象のマップ
     */
    public function putAll(Peach_Util_Map $map);

    /**
     * 指定されたキーによるマッピングが存在するかどうかを調べます.
     * マッピングが存在する場合に TRUE を返します.
     *
     * @param  mixed   $key キー
     * @return boolean      マッピングが存在する場合に TRUE
     */
    public function containsKey($key);

    /**
     * 指定されたキー名によるマッピングが存在する場合に, そのマッピングを削除します.
     * @param mixed $key キー
     */
    public function remove($key);

    /**
     * このマップを空にします.
     */
    public function clear();

    /**
     * 登録されているマッピングの個数を返します.
     * @return int
     */
    public function size();

    /**
     * このマップに登録されているすべての値を配列で返します.
     * 返される配列に対する操作はこのオブジェクトに反映されません.
     * @return array
     */
    public function values();

    /**
     * このマップに登録されているすべてのキーを配列で返します.
     * 返される配列に対する操作はこのオブジェクトに反映されません.
     * @return array
     */
    public function keys();

    /**
     * このマップのすべてのエントリーを {@link Peach_Util_MapEntry} オブジェクトの配列で返します.
     * 
     * @return array
     * @see    Peach_Util_MapEntry
     */
    public function entryList();
}
