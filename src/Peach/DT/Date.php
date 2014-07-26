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
 * DATE 型の時間オブジェクトです.
 * このクラスは年・月・日のフィールドをサポートします.
 * 
 * @package DT
 */
class Peach_DT_Date extends Peach_DT_AbstractTime
{
    /**
     * 年の値です.
     * @var int
     * @ignore
     */
    protected $year;
    
    /**
     * 月の値です.
     * @var int
     * @ignore
     */
    protected $month;
    
    /**
     * 日の値です.
     * @var int
     * @ignore
     */
    protected $date;
    
    /**
     * アクセスした時点の Peach_DT_Date オブジェクトを返します.
     * @return Peach_DT_Date
     */
    public static function now()
    {
        $time  = time();
        $year  = date("Y", $time);
        $month = date("n", $time);
        $date  = date("d", $time);
        return new self($year, $month, $date);
    }
    
    /**
     * 指定された文字列を解析して Peach_DT_Date オブジェクトに変換します.
     * $format が指定されていない場合は {@link Peach_DT_W3cDatetimeFormat::getInstance()}
     * を使って解析を行います.
     * ("YYYY-MM-DD" 形式の文字列を受理します.)
     * 
     * @param  string          $text   変換対象の文字列
     * @param  Peach_DT_Format $format 変換に使用するフォーマット
     * @return Peach_DT_Date           変換結果の Peach_DT_Date オブジェクト
     */
    public static function parse($text, Peach_DT_Format $format = null)
    {
        if (!isset($format)) {
            $format = Peach_DT_W3cDatetimeFormat::getInstance();
        }
        return $format->parseDate($text);
    }
    
    /**
     * 与えられた日付を表現する Peach_DT_Date オブジェクトを構築します.
     *
     * @param int $year  年
     * @param int $month 月
     * @param int $date  日
     */
    public function __construct($year, $month, $date)
    {
        $fields = new Peach_Util_ArrayMap();
        $fields->put(self::$YEAR,  intval($year));
        $fields->put(self::$MONTH, intval($month));
        $fields->put(self::$DATE,  intval($date));
        $this->init($fields);
    }
    
    /**
     * このオブジェクトの型 {@link Peach_DT_Time::TYPE_DATE} を返します.
     * @return int Peach_DT_Time::TYPE_DATE
     */
    public function getType()
    {
        return self::TYPE_DATE;
    }
    
    /**
     * (non-PHPdoc)
     * @see DT_Month#init($fields)
     * @ignore
     */
    protected function init(Peach_Util_Map $fields)
    {
        parent::init($fields);
        $this->year  = $fields->get(self::$YEAR);
        $this->month = $fields->get(self::$MONTH);
        $this->date  = $fields->get(self::$DATE);
    }
    
    /**
     * 年月日の不整合を調整します.
     * @ignore
     */
    protected function adjust(Peach_Util_Map $fields)
    {
        // 年と月の不整合を調整します.
        $this->adjustMonth($fields);
        
        $year  = $fields->get(self::$YEAR);
        $month = $fields->get(self::$MONTH);
        $date  = $fields->get(self::$DATE);
        
        // 日が1より小さい場合は、月の繰り下げ調整をします.
        if ($date < 1) {
            while ($date < 1) {
                $month --;
                if ($month === 0) {
                    $month = 12;
                    $year --;
                }
                $fields->put(self::$YEAR,  $year);
                $fields->put(self::$MONTH, $month);
                $date += self::getDateCountOf($year, $month);
            }
            $fields->put(self::$DATE, $date);
            $this->adjust($fields);
            return;
        }
        
        // 日がこの月の日数より大きい場合は、月の繰上げ調整をします.
        $dateCount = self::getDateCountOf($year, $month);
        if ($dateCount < $date) {
            while ($dateCount < $date) {
                $date -= $dateCount;
                $month ++;
                if ($month === 13) {
                    $month = 1;
                    $year ++;
                }
                $fields->put(self::$YEAR,  $year);
                $fields->put(self::$MONTH, $month);
                $dateCount = self::getDateCountOf($year, $month);
            }
            $fields->put(self::$DATE, $date);
            $this->adjust($fields);
            return;
        }
    }
    
    /**
     * 年と月の不整合を調整します.
     */
    private function adjustMonth(Peach_Util_Map $fields)
    {
        // 年の不整合を調整します.
        $this->adjustYear($fields);
        $adjuster = $this->getMonthAdjuster();
        $month    = $fields->get(self::$MONTH);
        if ($month < 1) {
            $adjuster->moveDown($fields);
        } else if (12 < $month) {
            $adjuster->moveUp($fields);
        } else {
            return;
        }
        $this->adjustMonth($fields);
    }
    
    /**
     * 月の調整をするための FieldAdjuster を返します.
     * 
     * @return Peach_DT_FieldAdjuster
     */
    private function getMonthAdjuster()
    {
        static $adjuster = null;
        if (!isset($adjuster)) {
            $adjuster = new Peach_DT_FieldAdjuster(self::$MONTH, self::$YEAR, 1, 12);
        }
        return $adjuster;
    }
    
    /**
     * 年の値を4桁に調整します. (10000年問題に対応するまでの暫定的処置です.)
     */
    private function adjustYear(Peach_Util_Map $fields)
    {
        $year = $fields->get(self::$YEAR);
        $year %= 10000;
        if ($year < 0) {
            $year += 10000;
        }
        $fields->put(self::$YEAR, $year);
    }
    
    /**
     * (non-PHPdoc)
     * @return Peach_DT_Date
     * @see Peach_DT_Time#newInstance($fields)
     * @ignore
     */
    protected function newInstance(Peach_Util_Map $fields)
    {
        $year  = $fields->get(self::$YEAR);
        $month = $fields->get(self::$MONTH);
        $date  = $fields->get(self::$DATE);
        return new self($year, $month, $date);
    }
    
    /**
     * この時間と指定された時間を比較します.
     *
     * この型の時間フィールドと引数の型の時間フィールドのうち、
     * 共通しているフィールド同士を比較します.
     * 
     * 引数が Peach_DT_Date を継承したオブジェクトではない場合, 
     * 引数のオブジェクトに対して get("year"), get("month"), get("date") の返り値を比較対象のフィールドとします.
     * 
     * @param  Peach_DT_Time 比較対象の時間
     * @return int     この時間のほうが過去の場合は負の値, 未来の場合は正の値, 等しい場合は 0
     * @ignore
     */
    protected function compareFields(Peach_DT_Time $time)
    {
        $className = __CLASS__;
        if ($time instanceof $className) {
            if ($this->year  !== $time->year) {
                return $this->year  - $time->year;
            }
            if ($this->month !== $time->month) {
                return $this->month - $time->month;
            }
            if ($this->date  !== $time->date) {
                return $this->date  - $time->date;
            }
            return 0;
        }
        else {
            $y = $time->get("year");
            $m = $time->get("month");
            $d = $time->get("date");
            if ($this->year  !== $y) {
                return (isset($y) ? $this->year  - $y : 0);
            }
            if ($this->month !== $m) {
                return (isset($m) ? $this->month - $m : 0);
            }
            if ($this->date  !== $d) {
                return (isset($d) ? $this->date  - $d : 0);
            }
            return 0;
        }
    }
    
    /**
     * @return string
     * @ignore
     */
    protected function handleFormat(Peach_DT_Format $format)
    {
        return $format->formatDate($this);
    }
    
    /**
     * このオブジェクトの文字列表現です.
     * "YYYY-MM-DD" 形式の文字列を返します.
     * 
     * @return string "YYYY-MM-DD" 形式の文字列
     */
    public function __toString()
    {
        $y = str_pad($this->year,  4, '0', STR_PAD_LEFT);
        $m = str_pad($this->month, 2, '0', STR_PAD_LEFT);
        $d = str_pad($this->date,  2, '0', STR_PAD_LEFT);
        return $y . '-' . $m . '-' . $d;
    }
    
    /**
     * このオブジェクトを Peach_DT_Date 型にキャストします.
     * 返り値は, このオブジェクトのクローンです.
     *
     * @return Peach_DT_Date このオブジェクトのクローン
     */
    public function toDate()
    {
        return new self($this->year, $this->month, $this->date);
    }
    
    /**
     * このオブジェクトを Peach_DT_Datetime 型にキャストします.
     * この日付の 0 時 0 分を表す Peach_DT_Datetime オブジェクトを返します.
     *
     * @return Peach_DT_Datetime このオブジェクトの時刻表現
     */
    public function toDatetime()
    {
        return new Peach_DT_Datetime($this->year, $this->month, $this->date, 0, 0);
    }
    
    /**
     * このオブジェクトを Peach_DT_Timestamp 型にキャストします.
     * この日付の 0 時 0 分 0 秒を表す Peach_DT_Timestamp オブジェクトを返します.
     *
     * @return Peach_DT_Timestamp このオブジェクトの時刻表現
     */
    public function toTimestamp()
    {
        return new Peach_DT_Timestamp($this->year, $this->month, $this->date, 0, 0, 0);
    }
    
    /**
     * この日付の曜日を返します. 返される値は 0 から 6 までの整数で, 0 が日曜, 6 が土曜をあらわします.
     * それぞれの整数は, このクラスで定義されている各定数に対応しています.
     * 
     * @return int 曜日 (0 以上 6 以下の整数)
     * 
     * @see    Peach_DT_Time::SUNDAY
     * @see    Peach_DT_Time::MONDAY
     * @see    Peach_DT_Time::TUESDAY
     * @see    Peach_DT_Time::WEDNESDAY
     * @see    Peach_DT_Time::THURSDAY
     * @see    Peach_DT_Time::FRIDAY
     * @see    Peach_DT_Time::SATURDAY
     */
    public function getDay()
    {
        return self::getDayOf($this->year, $this->month, $this->date);
    }
    
    /**
     * この年がうるう年かどうかを判定します.
     *
     * うるう年の判別ルールは以下の通りです.
     * - 4 で割り切れるはうるう年である
     * - ただし 100 で割り切れる年はうるう年ではない
     * - ただし 400 で割り切れる年はうるう年である
     * 
     * @return bool うるう年である場合に TRUE, それ以外は FALSE
     */
    public function isLeapYear()
    {
        return self::checkLeapYear($this->year);
    }
    
    /**
     * 指定された年がうるう年かどうかを判定します.
     * 
     * @return bool うるう年である場合に TRUE, それ以外は FALSE
     */
    private static function checkLeapYear($year)
    {
        if ($year % 4 != 0) {
            return false;
        }
        if ($year % 100 != 0) {
            return true;
        }
        return ($year % 400 == 0);
    }
    
    /**
     * この月の日数を返します.
     * @return int この月の日数. すなわち, 28 から 31 までの整数.
     */
    public function getDateCount()
    {
        return self::getDateCountOf($this->year, $this->month);
    }
    
    /**
     * 指定された月の日数を返します.
     * @param  int $year  年
     * @param  int $month 月
     * @return int 引数で指定された月の日数. すなわち, 28 から 31 までの整数.
     */
    private static function getDateCountOf($year, $month)
    {
        switch ($month) {
            case 1:  return 31;
            case 2:  return (self::checkLeapYear($year)) ? 29 : 28;
            case 3:  return 31;
            case 4:  return 30;
            case 5:  return 31;
            case 6:  return 30;
            case 7:  return 31;
            case 8:  return 31;
            case 9:  return 30;
            case 10: return 31;
            case 11: return 30;
            case 12: return 31;
            default:
                throw new Exception();
        }
    }
}
