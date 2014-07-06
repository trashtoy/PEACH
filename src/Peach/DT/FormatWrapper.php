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
 * 既存の Peach_DT_Format オブジェクトを機能拡張するためのラッパークラスです.
 * このクラスは Decorator パターンで設計されています.
 * @package DT
 */
class Peach_DT_FormatWrapper implements Peach_DT_Format
{
    /**
     * ラップする Peach_DT_Format オブジェクトです.
     * @var Peach_DT_Format
     */
    private $original;
    
    /**
     * 指定された Peach_DT_Format オブジェクトをラップする FormatWrapper を構築します.
     * @param Peach_DT_Format $original ラップ対象のオブジェクト
     */
    public function __construct(Peach_DT_Format $original)
    {
        $this->original = $original;
    }
    
    /**
     * ラップ対象の Peach_DT_Format オブジェクトを返します.
     * @return Peach_DT_Format ラップ対象のオブジェクト
     */
    public function getOriginal()
    {
        return $this->original;
    }
    
    /**
     * ラップ対象のオブジェクトの formatDate メソッドを実行します.
     * @param  Peach_DT_Date $d 書式化対象の時間オブジェクト
     * @return string オリジナルの formatDate の結果
     */
    public function formatDate(Peach_DT_Date $d)
    {
        return $this->original->formatDate($d);
    }
    
    /**
     * ラップ対象のオブジェクトの formatDatetime メソッドを実行します.
     * @param  Peach_DT_Datetime $d 書式化対象の時間オブジェクト
     * @return string オリジナルの formatDatetime の結果
     */
    public function formatDatetime(Peach_DT_Datetime $d)
    {
        return $this->original->formatDatetime($d);
    }
    
    /**
     * ラップ対象のオブジェクトの formatTimestamp メソッドを実行します.
     * @param  Peach_DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string オリジナルの formatTimestamp の結果
     */
    public function formatTimestamp(Peach_DT_Timestamp $d)
    {
        return $this->original->formatTimestamp($d);
    }
    
    /**
     * ラップ対象のオブジェクトの parseDate メソッドを実行します.
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time オリジナルの parseDate の結果
     */
    public function parseDate($format)
    {
        return $this->original->parseDate($format);
    }
    
    /**
     * ラップ対象のオブジェクトの parseDatetime メソッドを実行します.
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time オリジナルの parseDatetime の結果
     */
    public function parseDatetime($format)
    {
        return $this->original->parseDatetime($format);
    }
    
    /**
     * ラップ対象のオブジェクトの parseTimestamp メソッドを実行します.
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time オリジナルの parseTimestamp の結果
     */
    public function parseTimestamp($format)
    {
        return $this->original->parseTimestamp($format);
    }
}
