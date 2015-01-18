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
 * HTTP-Date の書式を扱うクラスです.
 * parse 系メソッドは, 以下の 3 種類のフォーマットを解釈することが出来ます.
 * 
 * <code>"Fri, 13 Feb 2009 23:31:30 GMT"   #RFC 822, updated by RFC 1123</code>
 * <code>"Friday, 13-Feb-09 23:31:30 GMT"  #RFC 850, obsoleted by RFC 1036</code>
 * <code>"Fri Feb 13 23:31:30 2009"        #ANSI C's asctime() format</code>
 * 
 * format 系メソッドは, 一番上の RFC 1123 形式のフォーマットで文字列を生成します.
 * 
 * このクラスは parse および format を行う際に, 内部時刻と GMT の自動変換を行います.
 * 
 * 参考文献:
 * {@link http://www.arielworks.net/articles/2004/0125a モジュール版PHPで「If-Modified-Since」に対応する}
 * 
 * @package DT
 */
class Peach_DT_HttpDateFormat implements Peach_DT_Format
{
    /**
     * システム時刻の時差です (単位は分)
     * @var int
     */
    private $internalOffset;

    /**
     * 新しいフォーマットを生成します.
     * 引数 $offset を指定した場合, parse または format を行った際に,
     * 指定された時差の量だけ時刻に補正がかかります.
     * もしも時差に応じた自動変換が必要ない場合は $offset に 0 を指定してください.
     * 
     * 引数を省略した場合は, システム時刻の時差 ({@link Peach_DT_Util::getTimeZoneOffset()} の返り値と等価)
     * を使用します. 特に必要がなければ引数なしのコンストラクタを使う代わりに
     * {@link Peach_DT_HttpDateFormat::getInstance() getInstance()} を使ってください.
     * 
     * @param int $offset 時間オブジェクトの時差 (単位は分, 省略した場合はシステム設定の値を使用)
     */
    public function __construct($offset = null)
    {
        $this->internalOffset = Peach_DT_Util::cleanTimeZoneOffset($offset);
    }

    /**
     * デフォルトのインスタンスを返します.
     * このメソッドは引数なしでコンストラクタを呼び出した場合と同じ結果を返しますが,
     * 返り値がキャッシュされるため, 複数の場所で同じインスタンスを使いまわすことができます.
     * 
     * システムのタイムゾーン設定を動的に変更した場合は, 変更前のキャッシュを破棄する必要があるため,
     * 引数 $clearCache に TRUE を指定して実行してください.
     * 
     * @param  bool $clearCache  キャッシュを破棄してインスタンスを再生成する場合は TRUE
     * @return Peach_DT_HttpDateFormat デフォルトのインスタンス
     */
    public static function getInstance($clearCache = false)
    {
        static $instance = null;
        if (!isset($instance) || $clearCache) {
            $instance = new self();
        }
        return $instance;
    }

    /**
     * {@link Peach_DT_HttpDateFormat::parseTimestamp() parseTimestamp()} の実行結果を Peach_DT_Date にキャストします.
     * 
     * @param  string $format           HTTP-date 形式の文字列
     * @return Peach_DT_Date            変換結果
     * @throws InvalidArgumentException フォーマットが不正な場合
     */
    public function parseDate($format)
    {
        return $this->parseTimestamp($format)->toDate();
    }

    /**
     * {@link Peach_DT_HttpDateFormat::parseTimestamp() parseTimestamp()} の実行結果を Peach_DT_Datetime にキャストします.
     * 
     * @param  string $format           HTTP-date 形式の文字列
     * @return Peach_DT_Datetime        変換結果
     * @throws InvalidArgumentException フォーマットが不正な場合
     */
    public function parseDatetime($format)
    {
        return $this->parseTimestamp($format)->toDatetime();
    }

    /**
     * HTTP-date 形式のフォーマットを Peach_DT_Timestamp に変換します.
     * 
     * @param  string $format           HTTP-date 形式の文字列
     * @return Peach_DT_Timestamp       変換結果
     * @throws InvalidArgumentException フォーマットが不正な場合
     */
    public function parseTimestamp($format)
    {
        $temp_date = array();
        if (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), ([0-3][0-9]) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-9]{4}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[5];
            $date["minute"] = $temp_date[6];
            $date["second"] = $temp_date[7];
            $date["month"]  = $this->parseMonthDescription($temp_date[3]);
            $date["day"]    = $temp_date[2];
            $date["year"]   = $temp_date[4];
        } else if (preg_match("/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday), ([0-3][0-9])-(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-([0-9]{2}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[5];
            $date["minute"] = $temp_date[6];
            $date["second"] = $temp_date[7];
            $date["month"]  = $this->parseMonthDescription($temp_date[3]);
            $date["day"]    = $temp_date[2];
            $date["year"]   = $this->getFullYear($temp_date[4]);
        } else if (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-3 ][0-9]) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) ([0-9]{4})$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[4];
            $date["minute"] = $temp_date[5];
            $date["second"] = $temp_date[6];
            $date["month"]  = $this->parseMonthDescription($temp_date[2]);
            // 日が1桁の場合先、半角スペースを0に置換
            $date["day"]    = str_replace(" ", "0", $temp_date[3]);
            // 定義済みの月の名前を数字に変換する
            $date["year"]   = $temp_date[7];
        } else {
            $this->throwFormatException($format);
        }

        $parsed = new Peach_DT_Timestamp($date["year"], $date["month"], $date["day"], $date["hour"], $date["minute"], $date["second"]);
        return $parsed->add("minute", - $this->internalOffset);
    }

    /**
     * この日付の 00:00 の時刻を GMT に変換した結果を Http-date にして返します.
     * 例えばシステム時刻の時差が UTC+9 だった場合, 前日の 15:00 の HTTP-date 表現を返り値とします.
     * 
     * @param  Peach_DT_Date $d 書式化対象の時間オブジェクト
     * @return string           この日付の HTTP-date 表現
     */
    public function formatDate(Peach_DT_Date $d)
    {
        return $this->formatDatetime($d->toDatetime());
    }

    /**
     * この時刻の HTTP-date 表現を返します.
     * 
     * @param  Peach_DT_Datetime $d 書式化対象の時間オブジェクト
     * @return string               この時刻の HTTP-date 表現
     */
    public function formatDatetime(Peach_DT_Datetime $d)
    {
        $d = $d->add("minute", $this->internalOffset);

        $format  = '';
        $format .= $this->getDayDescription($d->getDay()).', ';
        $format .= str_pad($d->get('date'), 2, '0', STR_PAD_LEFT) . ' ';
        $format .= $this->getMonthDescription($d->get('month'))   . ' ';
        $format .= str_pad($d->get('year'), 4, '0', STR_PAD_LEFT) . ' ';
        $format .= $d->formatTime() . ' ';
        $format .= 'GMT';
        return $format;
    }

    /**
     * この時刻の HTTP-date 表現を返します.
     * 
     * @param  Peach_DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string                この時刻の HTTP-date 表現
     */
    public function formatTimestamp(Peach_DT_Timestamp $d)
    {
        return $this->formatDatetime($d);
    }

    /**
     * parse に失敗した場合に呼び出されます.
     * 
     * @param  string $format parse に失敗した文字列
     * @throws InvalidArgumentException
     */
    private function throwFormatException($format)
    {
        throw new InvalidArgumentException("Illegal format({$format}). HTTP-date format required.");
    }

    /**
     * 月の略称一覧です
     * @return array "Jan" から "Dec" までの月の略称を持つ配列
     */
    private function getMonthMapping()
    {
        static $mapping = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        return $mapping;
    }

    /**
     * 月の略称を書式化します.
     * @param  int $month 月
     * @return string     引数の月の略称
     */
    private function getMonthDescription($month)
    {
        $mapping = $this->getMonthMapping();
        return $mapping[$month - 1];
    }

    /**
     * 月の略称を月に変換します.
     * @param  string $mon 月の略称
     * @return int 引数の略称の数値表現 (1 から 12)
     */
    private function parseMonthDescription($mon)
    {
        $mapping = $this->getMonthMapping();
        $month   = array_search($mon, $mapping) + 1;
        return str_pad($month, 2, '0', STR_PAD_LEFT);
    }

    /**
     * 2 桁の年表示を 4 桁に変換します.
     * もしも引数の 2 桁年が, 現在の 2 桁年よりも大きい場合,
     * 前世紀の年と判断します.
     * 
     * @param int $y 変換対象の 2 桁年
     */
    private function getFullYear($y)
    {
        $currentYear = intval(date("Y"));
        $century     = intval($currentYear / 100);
        $smallY      = $currentYear % 100;
        $resultC     = ($smallY < $y) ? $century - 1 : $century;
        return $resultC * 100 + $y;
    }

    /**
     * 曜日の略称を書式化します.
     * @param  int $day 曜日の値
     * @return string   曜日の略称
     */
    private function getDayDescription($day)
    {
        switch ($day) {
            case 0: return "Sun";
            case 1: return "Mon";
            case 2: return "Tue";
            case 3: return "Wed";
            case 4: return "Thu";
            case 5: return "Fri";
            case 6: return "Sat";
            default:
                throw new Exception("day: Out of range");
        }
    }
}
