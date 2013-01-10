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
require_once(dirname(__FILE__) . "/Map.php");

/**
 * PHP の配列機能を使った Map の実装です.
 * このマップはキーに整数または文字列しか使えないという制限があります.
 * 
 * @package Util
 */
class Util_ArrayMap implements Util_Map
{
    /**
     * マッピングを保持する配列です.
     * @var array
     */
    private $data;

    /**
     * 新しいインスタンスを構築します.
     * @param Util_Map|array $var 指定された配列またはマッピングでこのマップを初期化します.
     */
    public function __construct($var = NULL)
    {
        if ($var instanceof Util_ArrayMap) {
            $this->data = $var->data;
        } else if ($var instanceof Util_Map) {
            $this->data = array();
            $entryList = $var->entryList();
            foreach ($entryList as $entry) {
                $this->put($entry->getKey(), $entry->getValue());
            }
        } else if (!isset($var)) {
            $this->data = array();
        } else if (is_array($var)) {
            $this->data = $var;
        } else {
            throw new Exception("Argument (" . gettype($var) . ") must be array or Util_Map");
        }
    }

    /**
     * 指定されたキー名にマッピングされている値を返します.
     * マッピングが存在しない場合は代替値 (デフォルトは NULL) を返します.
     * このメソッドの返り値が NULL (または指定した代替値) の場合, 必ずしもマッピングが存在しないとは限りません.
     * マッピングが存在するかどうかを調べる場合は {@link Util_ArrayMap::containsKey()} を使用してください.
     * 
     * @param  string $name         キー名
     * @param  mixed  $defaultValue デフォルト値
     * @return mixed  指定したキーに関連づけられた値. 存在しない場合は $defaultValue を返す
     */
    public function get($name, $defaultValue = NULL)
    {
        return (isset($this->data[$name])) ? $this->data[$name] : $defaultValue;
    }

    /**
     * 指定されたキー名と値を関連づけます.
     * この実装では, 内部に保存されている配列に対して
     * <code>$arr[$key] = $value;</code> を実行するのと同等の操作を行います.
     * もしも $key に非スカラー値 (オブジェクトや配列など) が指定された場合は, 
     * {@link Util_Values::stringValue()} で string 型に変換した結果をキーとします.
     * 
     * @param  string $key   キー名
     * @param  mixed  $value 指定されたキーに関連づける値
     */
    public function put($key, $value)
    {
        if (!is_scalar($key)) {
            $key = Util_Values::stringValue($key);
        }
        $this->data[$key] = $value;
    }

    /**
     * 指定された Map のマッピングをすべて登録します.
     * 
     * @param Util_Map $map
     * @see   Util_Map::putAll($map)
     */
    public function putAll(Util_Map $map)
    {
        $entryList = $map->entryList();
        foreach ($entryList as $entry) {
            $this->put($entry->getKey(), $entry->getValue());
        }
    }

    /**
     * 指定されたキー名によるマッピングが存在するかどうかを調べます.
     * マッピングが存在する場合に TRUE を返します.
     *
     * @param  string  $name キー名
     * @return boolean マッピングが存在する場合に TRUE
     */
    public function containsKey($name)
    {
        return isset($this->data[$name]);
    }

    /**
     * 指定されたキー名によるマッピングが存在する場合に, そのマッピングを削除します.
     * @param string $key キー名
     */
    public function remove($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        }
    }

    /**
     * このマップを空にします.
     */
    public function clear()
    {
        $this->data = array();
    }

    /**
     * 登録されているマッピングの個数を返します.
     * @return int
     */
    public function size()
    {
        return count($this->data);
    }

    /**
     * このマップに登録されているすべてのキーを配列で返します.
     * 返される配列に対する操作はこのマップには反映されません.
     * 
     * @return array
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * このマップに登録されているすべての値を配列で返します.
     * 返される配列に対する操作はこのマップには反映されません.
     * @return array
     */
    public function values()
    {
        return array_values($this->data);
    }

    /**
     * このマップに含まれるすべてのエントリーを返します.
     * 
     * @return array {@link Util_MapEntry} の配列
     */
    public function entryList()
    {
        $result = array();
        foreach ($this->data as $key => $value) {
            $result[] = new Util_ArrayMapEntry($key, $value, $this);
        }
        return $result;
    }

    /**
     * このマップに登録されているエントリーを配列として返します.
     * 返される配列に対する操作はこのマップには反映されません.
     * 
     * @return array このマップの配列表現
     */
    public function asArray()
    {
        return $this->data;
    }
}
?>