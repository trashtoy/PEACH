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
 * デフォルトの {@link Peach_Util_MapEntry} の実装です.
 * このクラスでは getKey(), getValue() のみ実装されています.
 * 
 * @package Util
 */
abstract class Peach_Util_AbstractMapEntry implements Peach_Util_MapEntry
{
    /**
     * マッピングのキーです.
     * @var mixed
     */
    protected $key;
    
    /**
     * マッピングの値です.
     * @var mixed
     */
    protected $value;
    
    /**
     * 新しいエントリーオブジェクトを構築します.
     * @param mixed キー
     * @param mixed 値
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }
    
    /**
     * このエントリーのキーを返します.
     * @return mixed このエントリーのキー
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * このエントリーの値を返します.
     * @return mixed このエントリーの値
     */
    public function getValue()
    {
        return $this->value;
    }
}
?>