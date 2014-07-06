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
 * 時間関連のユーティリティクラスです. 
 * 
 * @package DT
 */
class Peach_DT_Util
{
    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}
        
    /**
     * 2つの時間オブジェクトを比較します. 一つめの引数のほうが後 (未来) の場合は正の値,
     * 前 (過去) の場合は負の値, 同じ場合は 0 を返します.
     * 
     * このメソッドは, 一つ目の時間オブジェクトの {@link Peach_DT_Time::compareTo()} メソッドを呼び出し
     * 二つ目の時間オブジェクトと比較した結果を返します.
     *
     * @param  Peach_DT_Time $time1 1つ目の時間
     * @param  Peach_DT_Time $time2 2つ目の時間
     * @return int                  1つ目の時間を2つ目の時間と比較した結果
     * @see    Peach_DT_Time::compareTo()
     */
    public static function compareTime(Peach_DT_Time $time1, Peach_DT_Time $time2)
    {
        return $time1->compareTo($time2);
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最も古いものを返します.
     * 引数に Peach_DT_Time 型オブジェクトを羅列するか, Peach_DT_Time 型オブジェクトの配列を指定してください.
     * 引数に Peach_DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param array|Peach_DT_Time $time...
     * @return Peach_DT_Time      引数の中で最も古い Peach_DT_Time オブジェクト. 
     *                            引数が空か, Peach_DT_Time オブジェクトが含まれていない場合は NULL
     */
    public static function oldest()
    {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Peach_Util_Arrays::min(Peach_Util_Arrays::pickup($args, "Peach_DT_Time"));
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最新のものを返します. 
     * 引数に Peach_DT_Time 型オブジェクトを羅列するか, Peach_DT_Time 型オブジェクトの配列を指定してください.
     * 引数に Peach_DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param  array|Peach_DT_Time $time...
     * @return Peach_DT_Time       引数の中で最新の Peach_DT_Time オブジェクト. 
     *                             引数が空か, Peach_DT_Time オブジェクトが含まれていない場合は NULL
     */
    public static function latest()
    {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Peach_Util_Arrays::max(Peach_Util_Arrays::pickup($args, "Peach_DT_Time"));
    }
    
    /**
     * 引数に指定された年・月・日 (オプションで時・分・秒) の妥当性を検証します.
     * 妥当な組み合わせの場合は TRUE, それ以外は FALSE を返します.
     * 引数に数値以外の型が含まれていた場合, それが数値文字列
     * ({@link is_numeric() is_numeric()} が TRUE を返す)
     * の場合のみ妥当とみなします.
     * 
     * @param int $year   年
     * @param int $month  月
     * @param int $date   日
     * @param int $hour   時
     * @param int $minute 分
     * @param int $second 秒
     * @return bool 組み合わせが妥当な場合に TRUE, それ以外は FALSE
     */
    public static function validate($year, $month, $date, $hour = 0, $minute = 0, $second = 0)
    {
        $test = array("year" => $year, "month" => $month, "date" => $date, "hour" => $hour, "minute" => $minute, "second" => $second);
        $d    = new Peach_DT_Timestamp($year, $month, $date, $hour, $minute, $second);
        foreach ($test as $key => $value) {
            if (!is_numeric($value) || $d->get($key) !== intval($value)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * システム時刻と GMT との時差を, 分単位で返します.
     * 
     * システム時刻が GMT よりも未来の場合 (UTC + 1 以上) は負の値,
     * システム時刻が GMT よりも過去の場合 (UTF - 1 以下) は正の値を返します.
     * 例えばタイムゾーンが "Asia/Tokyo" (+09:00) の場合,
     * 返り値は -540 となります.
     * 
     * @return int
     */
    public static function getTimeZoneOffset()
    {
        $local = mktime(0, 0, 0, 1, 1, 1970);
        $gmt   = gmmktime(0, 0, 0, 1, 1, 1970);
        return ($local - $gmt) / 60;
    }
    
    /**
     * 指定された時差 (分単位) の値が妥当かどうかを調べ, 必要に応じて値を丸めた結果を返します.
     * 世界で実際に使用されているタイムゾーンは UTC-12 から UTC+14 ですが,
     * このメソッドでは -23:45 から +23:45 を妥当なタイムゾーンとみなします.
     * もしも -23:45 以前の時差が指定された場合は -23:45 (1425), 
     * +23:45 以降の時差が指定された場合は +23:45 (-1425) に丸めた結果を返します.
     * 
     * もしも NULL が指定された場合は {@link Peach_DT_Util::getTimeZoneOffset()} の結果を返します.
     * それ以外の数値以外の値が指定された場合は, 整数にキャストした値を使用します.
     * 
     * @param  int $offset
     * @return int 引数を -1425 以上 1425 以下に丸めた値
     */
    public static function cleanTimeZoneOffset($offset)
    {
        return isset($offset) ?
            Peach_Util_Values::intValue($offset, -1425, 1425) : // -23:45 から +23:45 まで
            Peach_DT_Util::getTimeZoneOffset();
    }
}
