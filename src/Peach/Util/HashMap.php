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
 * 任意の値やオブジェクトをキーに指定することが出来る Map です. 格納のアルゴリズムは
 * {@link http://docs.oracle.com/javase/jp/7/api/java/util/HashMap.html java.util.HashMap}
 * を参考にしています.
 * キーに使用するオブジェクトは, 出来る限り不変 (イミュータブル) なものを使用してください.
 * キーに設定したオブジェクトに対して外部から変更が加わった場合,
 * このオブジェクトの動作は保障されません.
 * 
 * @package Util
 */
class Peach_Util_HashMap implements Peach_Util_Map
{
    /**
     * このマップが持つエントリーの一覧です. 
     * この値は以下のような構造となります. (値は Peach_Util_HashMapEntry オブジェクト)
     * 
     * <pre>
     * array(
     *     [0] => array(
     *         [0] => entry1
     *         [1] => entry4
     *         [2] => entry5
     *     )
     *     [1] => array(
     *         [0] => entry2
     *         [1] => entry3
     *     )
     *     [2] => ...
     * )
     * </pre>
     * 
     * @var array
     */
    private $table;
    
    /**
     * 格納先のインデックスの個数です.
     * 常に 2 の累乗の値になります.
     * 
     * @var int
     */
    private $capacity;
    
    /**
     * キーの等価性のチェックを行うために使用する Equator です.
     * @var Peach_Util_Equator
     */
    private $equator;
    
    /**
     * put() や remove() などで内部構造に変化が加えられたことを示すフラグです.
     * {@link Peach_Util_HashMap::entryList()} を呼び出した際にキャッシュを使用するかどうか
     * 判断するために使用します.
     * 
     * @var bool
     */
    private $modFlag;
    
    /**
     * {@link Peach_Util_HashMap::entryList()} の返り値のキャッシュデータです.
     * この値はオブジェクトの内部構造が変化すると無効化されます.
     * 
     * @var array
     */
    private $cache;
    
    /**
     * 新しい HashMap を構築します.
     * 引数で設定された容量は, オブジェクト構築後に変更することは出来ません.
     * 
     * @param  Peach_Util_Map|array $map      デフォルトのマッピング (オプション)
     * @param  Peach_Util_Equator   $e        オブジェクトの等価性を判断するための Equator
     *                                        (NULL の場合は {@link Peach_Util_DefaultEquator} が適用されます)
     * @param  int                  $capacity 容量 (デフォルトは 16, 最小で 2)
     */
    public function __construct(Peach_Util_Map $map = null, Peach_Util_Equator $e = null, $capacity = 16)
    {
        $this->table    = array();
        $this->equator  = isset($e) ? $e : Peach_Util_DefaultEquator::getInstance();
        $this->capacity = self::detectCapacity($capacity);
        $this->modFlag  = true;
        $this->cache    = array();
        if (isset($map)) {
            $this->initTable($map);
        }
    }
    
    /**
     * コンストラクタの第一引数が指定された場合に実行される,
     * マッピングの初期化処理です.
     * 
     * @param Peach_Util_Map|array $map 
     */
    private function initTable(&$map)
    {
        if ($map instanceof Peach_Util_Map) {
            $entryList = $map->entryList();
            foreach ($entryList as $entry) {
                $this->put($entry->getKey(), $entry->getValue());
            }
            return;
        }
        if (is_array($map)) {
            foreach ($map as $key => $value) {
                $this->put($key, $value);
            }
            return;
        }
        
        throw new Exception("\$map must be Peach_Util_Map or array.");
    }
    
    /**
     * このマップの容量を計算します.
     * 引数以上で最小の 2 の累乗となる整数を返します.
     * @param int 容量
     */
    private static function detectCapacity($capacity)
    {
        if ($capacity < 2) {
            return 2;
        }
        
        $i = 2;
        while ($i < $capacity) {
            $i *= 2;
        }
        return $i;
    }
    
    /**
     * 指定されたキーと値をこの Map に関連づけます.
     * @param mixed キー
     * @param mixed 値
     */
    public function put($key, $value)
    {
        $index = $this->getIndexOf($key);
        if (!isset($this->table[$index])) {
            $this->table[$index] = array();
        }
        foreach ($this->table[$index] as $entry) {
            if ($entry->keyEquals($key, $this->equator)) {
                $entry->setValue($value);
                $this->modFlag = true;
                return;
            }
        }
        $this->table[$index][] = $this->createEntry($key, $value);
        $this->modFlag = true;
    }

    /**
     * 指定された Map の中身をすべて追加します。
     * もしも引数の Map とこの Map に同じキーが存在していた場合, 
     * 引数のマッピングで上書きされます.
     * 
     * @param Peach_Util_Map $map 格納される Map
     * @see   Peach_Util_Map::putAll($map)
     */
    public function putAll(Peach_Util_Map $map)
    {
        $entryList = $map->entryList();
        foreach ($entryList as $entry) {
            $this->put($entry->getKey(), $entry->getValue());
        }
    }
    
    /**
     * 指定されたキーにマッピングされている値を返します.
     * マッピングが存在しない場合は代替値 (デフォルトは NULL) を返します.
     * このメソッドの返り値が NULL (または指定した代替値) の場合, 必ずしもマッピングが存在しないとは限りません.
     * マッピングの存在を確認する場合は {@link Peach_Util_HashMap::conainsKey($key)} を使用してください.
     * 
     * @param  mixed $key          マッピングのキー
     * @param  mixed $defaultValue マッピングが存在しない場合に返される代替値
     * @return mixed
     */
    public function get($key, $defaultValue = null)
    {
        $index = $this->getIndexOf($key);
        if (!isset($this->table[$index])) {
            return $defaultValue;
        }
        foreach ($this->table[$index] as $entry) {
            if ($entry->keyEquals($key, $this->equator)) {
                return $entry->getValue();
            }
        }
        return $defaultValue;
    }
    
    /**
     * マッピングを空にします.
     */
    public function clear()
    {
        $this->table = array();
        $this->modFlag = true;
    }
    
    /**
     * この Map が持つマッピングの個数を返します.
     * @return int
     * @see    Peach_Util_Map::size()
     */
    public function size()
    {
        $size = 0;
        foreach ($this->table as $entries) {
            $size += count($entries);
        }
        return $size;
    }
    
    /*
     * この HashMap に含まれるキーの一覧を返します.
     * @return array
     */
    public function keys()
    {
        $result = array();
        foreach ($this->table as $entries) {
            foreach ($entries as $entry) {
                $result[] = $entry->getKey();
            }
        }
        return $result;
    }
    
    /**
     * 指定されたキーによるマッピングが存在するかどうかを調べます.
     * マッピングが存在する場合に TRUE を返します.
     * 
     * @param  mixed   キー
     * @return boolean マッピングが存在する場合に TRUE
     */
    public function containsKey($key)
    {
        $index = $this->getIndexOf($key);
        if (!isset($this->table[$index])) {
            return false;
        }
        foreach ($this->table[$index] as $entry) {
            if ($entry->keyEquals($key, $this->equator)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * 指定されたキーのマッピングを削除します.
     * @param mixed キー
     */
    public function remove($key)
    {
        $index = $this->getIndexOf($key);
        if (!isset($this->table[$index])) {
            return;
        }
        foreach ($this->table[$index] as $i => $entry) {
            if ($entry->keyEquals($key, $this->equator)) {
                array_splice($this->table[$index], $i, 1);
                $this->modFlag = true;
                return;
            }
        }
        return;
    }
    
    /**
     * このマップに登録されているすべての値を配列で返します.
     * 返される配列に対する操作はこのマップには反映されません.
     * @return array
     */
    public function values()
    {
        $result = array();
        foreach ($this->table as $entries) {
            foreach ($entries as $entry) {
                $result[] = $entry->getValue();
            }
        }
        return $result;
    }
    
    /**
     * この HashMap に登録されているすべてのエントリーを返します.
     * 
     * @return array {@link Peach_Util_HashMapEntry} の配列
     */
    public function entryList()
    {
        if ($this->modFlag) {
            $this->cache = array();
            foreach ($this->table as $entries) {
                foreach ($entries as $entry) {
                    $this->cache[] = $entry;
                }
            }
            $this->modFlag = false;
        }
        return $this->cache;
    }
    
    /**
     * 指定されたキーと値をマッピングする, 新しいエントリーを構築します.
     * ユーザーは, 必要に応じてこのメソッドをオーバーライドし,
     * 機能拡張した {@link Peach_Util_HashMapEntry HashMapEntry} を返すようにすることもできます.
     * 
     * @param  mixed マッピングのキー.
     * @param  mixed マッピングの値.
     * @return Peach_Util_HashMapEntry
     */
    protected function createEntry($key, $value)
    {
        return new Peach_Util_HashMapEntry($key, $value);
    }
    
    /**
     * 指定されたキーのインデックスを返します.
     * @param  string $key
     * @return int
     */
    private function getIndexOf($key)
    {
        $hash = $this->equator->hashCode($key);
        return ($this->capacity - 1) & $hash;
    }
}
?>