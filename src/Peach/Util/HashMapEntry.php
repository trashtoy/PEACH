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
/**
 * @package Util 
 * @ignore
 */
/**
 * {@link Peach_Util_HashMap} の内部で使われる MapEntry です.
 * 
 * @package Util
 * @see     Peach_Util_HashMap::createEntry()
 */
class Peach_Util_HashMapEntry extends Peach_Util_AbstractMapEntry
{
    /**
     * このエントリーのキーと引数のキーを比較します.
     * このメソッドは {@link Peach_Util_HashMap::put()} または
     * {@link Peach_Util_HashMap::containsKey()} でキーが既に存在するかどうか調べるために使用されます.
     * このメソッドは, Java で例えると「パッケージプライベート」に相当します.
     * エンドユーザーが直接使う機会はありません.
     * 
     * @param  mixed $key 比較対象のキー
     * @param  Util_Equator $e 比較に使用する Equator
     * @return bool  このエントリーのキーと引数が等しい場合に true
     */
    public function keyEquals($key, Peach_Util_Equator $e)
    {
        return $e->equate($this->key, $key);
    }

    /**
     * このエントリーの値を新しい値に更新します.
     * 
     * @param mixed $value 新しい値
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
