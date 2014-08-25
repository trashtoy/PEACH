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
/** @package DT */
/**
 * 文字列から時間オブジェクトへの変換と, 時間オブジェクトから文字列への変換をサポートするインタフェースです.
 * このインタフェースで定義されている各メソッドは, 以下の場所から使われることを想定しています.
 * ユーザー自身がこれらのメソッドを呼び出す機会は基本的にありません.
 * 
 * - {@link Peach_DT_Time::format()}
 * - {@link Peach_DT_Date::parse()}
 * - {@link Peach_DT_Datetime::parse()}
 * - {@link Peach_DT_Timestamp::parse()}
 * 
 * @package DT
 */
interface Peach_DT_Format
{
    /**
     * 指定された文字列を解析して DATE 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time  解析結果
     */
    public function parseDate($format);
    
    /**
     * 指定された文字列を解析して DATETIME 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time  解析結果
     */
    public function parseDatetime($format);
    
    /**
     * 指定された文字列を解析して TIMESTAMP 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time  解析結果
     */
    public function parseTimestamp($format);
    
    /**
     * 指定された Peach_DT_Date オブジェクトを書式化します.
     * 
     * @param  Peach_DT_Date $d 書式化対象の時間オブジェクト
     * @return string           このフォーマットによる文字列表現
     */
    public function formatDate(Peach_DT_Date $d);
    
    /**
     * 指定された Peach_DT_Datetime オブジェクトを書式化します.
     * 
     * @param  Peach_DT_Datetime $d 書式化対象の時間オブジェクト
     * @return string               このフォーマットによる文字列表現
     */
    public function formatDatetime(Peach_DT_Datetime $d);
    
    /**
     * 指定された Peach_DT_Timestamp オブジェクトを書式化します.
     * 
     * @param  Peach_DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string                このフォーマットによる文字列表現
     */
    public function formatTimestamp(Peach_DT_Timestamp $d);
}
