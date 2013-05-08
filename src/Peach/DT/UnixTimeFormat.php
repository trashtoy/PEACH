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
 * Unix time ({@link time() time()} の返り値や {@link date() date()} の引数として使用される整数) 
 * と時間オブジェクトの相互変換を行うクラスです.
 * 
 * このクラスはシングルトンです. {@link DT_HttpDateFormat::getInstance() getInstance()}
 * からオブジェクトを取得してください.
 * 
 * @package DT
 */
class Peach_DT_UnixTimeFormat implements Peach_DT_Format
{
    /**
     * このクラスは getInstance() からインスタンス化します.
     */
    private function __construct() {}
    
    /**
     * このクラスのインスタンスを取得します.
     * @return Peach_DT_UnixTimeFormat
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * 指定されたタイムスタンプを Peach_DT_Date に変換します.
     * 
     * @param  string  タイムスタンプ
     * @return Peach_DT_Date 変換結果
     */
    public function parseDate($format)
    {
        $time  = intval($format);
        $year  = @date("Y", $time);
        $month = @date("n", $time);
        $date  = @date("d", $time);
        return new Peach_DT_Date($year, $month, $date);
    }
    
    /**
     * 指定されたタイムスタンプを Peach_DT_Datetime に変換します.
     * 
     * @param  string      タイムスタンプ
     * @return Peach_DT_Datetime 変換結果
     */
    public function parseDatetime($format)
    {
        $time  = intval($format);
        $year  = @date("Y", $time);
        $month = @date("n", $time);
        $date  = @date("d", $time);
        $hour  = @date("H", $time);
        $min   = @date("i", $time);
        return new Peach_DT_Datetime($year, $month, $date, $hour, $min);
    }
    
    /**
     * 指定されたタイムスタンプを Peach_DT_Timestamp に変換します.
     * @param  string       タイムスタンプ
     * @return Peach_DT_Timestamp 変換結果
     */
    public function parseTimestamp($format)
    {
        $time  = intval($format);
        $year  = @date("Y", $time);
        $month = @date("n", $time);
        $date  = @date("d", $time);
        $hour  = @date("H", $time);
        $min   = @date("i", $time);
        $sec   = @date("s", $time);
        return new Peach_DT_Timestamp($year, $month, $date, $hour, $min, $sec);
    }
    
    /**
     * 指定されたオブジェクトを Peach_DT_Timestamp 型にキャストして
     * {@link DT_HttpDateFormat::formatTimestamp() formatTimestamp()}
     * を実行した結果を返します.
     * 
     * @param  Peach_DT_Date $d 書式化する時間オブジェクト
     * @return string           指定された日付の 0 時 0 分 0 秒のタイムスタンプ
     */
    public function formatDate(Peach_DT_Date $d)
    {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定されたオブジェクトを Peach_DT_Timestamp 型にキャストして
     * {@link DT_HttpDateFormat::formatTimestamp() formatTimestamp()}
     * を実行した結果を返します.
     * 
     * @param  Peach_DT_Datetime $d 書式化する時間オブジェクト
     * @return string               指定された時刻の 0 秒のタイムスタンプ
     */
    public function formatDatetime(Peach_DT_Datetime $d)
    {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定された時刻をタイムスタンプに変換します.
     * 
     * このメソッドは、引数の Peach_DT_Timestamp オブジェクトが持つ各フィールド
     * (年月日・時分秒) の値から {@link mktime() mktime()} を行い, 
     * その結果を返り値とします.
     * 
     * ただし, 返り値が string 型となることに注意してください.
     * 
     * @param  Peach_DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string                指定された時刻のタイムスタンプ
     */
    public function formatTimestamp(Peach_DT_Timestamp $d)
    {
        $hour  = $d->get('hour');
        $min   = $d->get('minute');
        $sec   = $d->get('second');
        $month = $d->get('month');
        $date  = $d->get('date');
        $year  = $d->get('year');
        return strval(mktime($hour, $min, $sec, $month, $date, $year));
    }
}
?>