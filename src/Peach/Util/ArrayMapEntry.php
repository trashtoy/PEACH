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
/**
 * @package Util
 * @ignore
 */
/**
 * {@link Peach_Util_ArrayMap} の entryList() から生成されるオブジェクトです.
 * 
 * @package Util
 */
class Peach_Util_ArrayMapEntry extends Peach_Util_AbstractMapEntry
{
    /**
     * このエントリーが登録されている ArrayMap です.
     * @var Peach_Util_ArrayMap
     */
    private $map;

    /**
     * 新しい ArrayMapEntry を構築します.
     * 
     * @param mixed               $key   キー
     * @param mixed               $value キーに関連づけられた値
     * @param Peach_Util_ArrayMap $map   このエントリーが属する ArrayMap
     */
    public function __construct($key, $value, Peach_Util_ArrayMap $map)
    {
        parent::__construct($key, $value);
        $this->map = $map;
    }

    /**
     * このエントリーの値を更新します.
     * @param mixed $value 新しくセットされる値
     */
    public function setValue($value)
    {
        $this->map->put($this->key, $value);
        $this->value = $value;
    }

    /**
     * この MapEntry の文字列表現です.
     * @return string
     */
    public function __toString()
    {
        return "[{$this->key}={$this->value}]";
    }
}
?>