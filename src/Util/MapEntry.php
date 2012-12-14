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
 * マップに含まれるキーと値のペアをあらわすインタフェースです.
 * {@link Util_Map::entryList()} の返り値として使用されます.
 * 
 * @package Util
 */
interface Util_MapEntry {
    /**
     * このエントリーのキーです.
     * 
     * @return mixed このエントリーのキー
     */
    public function getKey();
    
    /**
     * このエントリーの値です.
     * 
     * @return mixed このエントリーの値.
     */
    public function getValue();
    
    /**
     * このエントリーの値を新しい値に更新します. 更新はもとの Map に反映されます.
     * ただし, このエントリーがもとのマップから削除されている場合は
     * このメソッドの動作は定義されません.
     * 
     * @param mixed $value 新しい値
     */
    public function setValue($value);
}
?>