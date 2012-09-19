<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Date.php");
/**
 * 時刻を表すクラスです.
 * @package DT
 */
class DT_Datetime extends DT_Date {
    /**
     * 時を表す整数(0～23)です.
     * @var int
     */
    protected $hour = 0;
    
    /**
     * 分を表す整数(0～59)です.
     * @var int
     */
    protected $minute = 0;
    
    /**
     * 現在時刻の DT_Datetime オブジェクトを返します.
     * 
     * @return DT_Datetime
     */
    public static function now() {
        $year  = @date('Y');
        $month = @date('n');
        $date  = @date('d');
        $hour  = @date('H');
        $min   = @date('i');
        return new self($year, $month, $date, $hour, $min);
    }
    
    /**
     * 指定されたテキストをパースして DT_Datetime オブジェクトに変換します.
     * @param  string      変換対象の文字列
     * @param  DT_Format   変換に使用するフォーマット
     * @return DT_Datetime 変換結果
     */
    public static function parse($text, DT_Format $format = NULL) {
        if (!isset($format)) {
            $format = DT_W3CDatetimeFormat::getDefault();
        }
        return $format->parseDatetime($text);
    }
    
    /**
     * 与えられた時刻を表現する DT_Datetime オブジェクトを構築します.
     *
     * @param int $year  年
     * @param int $month 月
     * @param int $date  日
     * @param int $hour  時
     * @param int $min   分
     */
    public function __construct($year, $month, $date, $hour, $min) {
        $fields = new Util_ArrayMap();
        $fields->put(self::$YEAR,   intval($year));
        $fields->put(self::$MONTH,  intval($month));
        $fields->put(self::$DATE,   intval($date));
        $fields->put(self::$HOUR,   intval($hour));
        $fields->put(self::$MINUTE, intval($min));
        $this->init($fields);
    }
    
    /**
     * このオブジェクトの型 {@link DT_Time::TYPE_DATETIME} を返します.
     * @return int
     */
    public function getType() {
        return self::TYPE_DATETIME;
    }
    
    /**
     * @ignore
     */
    protected function init(Util_Map $fields) {
        parent::init($fields);
        $this->hour   = $fields->get(self::$HOUR);
        $this->minute = $fields->get(self::$MINUTE);
    }
    
    /**
     * 時刻の不整合を調整します.
     * @ignore
     */
    protected function adjust(Util_Map $fields) {
        parent::adjust($fields);        
        $hourAd = $this->getHourAdjuster();
        $minAd  = $this->getMinuteAdjuster();
        $hour   = $fields->get(self::$HOUR);
        $min    = $fields->get(self::$MINUTE);
        
        if ($hour < 0) {
            $hourAd->moveDown($fields);
        }
        else if (23 < $hour) {
            $hourAd->moveUp($fields);
        }
        else if ($min < 0) {
            $minAd->moveDown($fields);
        }
        else if (59 < $min) {
            $minAd->moveUp($fields);
        }
        else {
            return;
        }
        
        $this->adjust($fields);
    }
    
    /**
     * (non-PHPdoc)
     * 
     * @return DT_Datetime
     * @see DT/DT_Time#newInstance($fields)
     * @ignore
     */
    protected function newInstance(Util_Map $fields) {
        $year  = $fields->get(self::$YEAR);
        $month = $fields->get(self::$MONTH);
        $date  = $fields->get(self::$DATE);
        $hour  = $fields->get(self::$HOUR);
        $min   = $fields->get(self::$MINUTE);
        return new self($year, $month, $date, $hour, $min);        
    }
    
    /**
     * この時間と指定された時間を比較します.
     * 
     * この型の時間フィールドと引数の型の時間フィールドのうち、
     * 共通しているフィールド同士を比較します.
     * 
     * 引数がこのクラスを継承したオブジェクトではない場合, 
     * 引数のオブジェクトに対して get("year"), get("month"), get("date"), get("hour"), get("minute")
     * を呼び出した結果を比較対象のフィールドとします.
     * 
     * @param  Util_Time 比較対象の時間
     * @return int       この時間のほうが過去の場合は負の値, 未来の場合は正の値, それ以外は 0
     * @ignore
     */
    protected function compareFields(DT_Time $time) {
        $c = parent::compareFields($time);
        if ($c !== 0) return $c;
        $className = __CLASS__;
        if ($time instanceof $className) {
            if ($this->hour   !== $time->hour)   return $this->hour   - $time->hour;
            if ($this->minute !== $time->minute) return $this->minute - $time->minute;
            return 0;
        }
        else {
            $h = $time->get("hour");
            $m = $time->get("minute");
            if ($this->hour   !== $h) return (isset($h) ? $this->hour   - $h : 0);
            if ($this->minute !== $m) return (isset($m) ? $this->minute - $m : 0);
            return 0;
        }
    }
    
    /**
	 * @ignore
     */
    protected function handleFormat(DT_Format $format) {
        return $format->formatDatetime($this);
    }
    
    /**
     * "hh:mm" 形式の文字列を返します.
     * 
     * @return string このオブジェクトの時刻表現
     */
    public function formatTime() {
        $hour = str_pad($this->hour,   2, '0', STR_PAD_LEFT);
        $min  = str_pad($this->minute, 2, '0', STR_PAD_LEFT);
        return $hour . ":" . $min;
    }
    
    /**
     * このオブジェクトの文字列表現です.
     * "YYYY-MM-DD hh:mm" 形式の文字列を返します.
     * 
     * @return string W3CDTF に則った文字列表現
     */
    public function __toString() {
        $date = parent::__toString();
        return $date . ' ' . $this->formatTime();
    }
    
    /**
     * このオブジェクトを DT_Datetime 型にキャストします.
     * 返り値はこのオブジェクトのクローンです.
     *
     * @return DT_Datetime このオブジェクトのクローン
     */
    public function toDatetime() {
        return new self($this->year, $this->month, $this->date, $this->hour, $this->minute);
    }
    
    /**
     * このオブジェクトを DT_Timestamp 型にキャストします.
     * この時刻の 0 秒を表す DT_Timestamp オブジェクトを返します.
     *
     * @return DT_Timestamp このオブジェクトの timestamp 表現
     */
    public function toTimestamp() {
        return new DT_Timestamp($this->year, $this->month, $this->date, $this->hour, $this->minute, 0);
    }
    
    /**
     * 「時」フィールドを調整する Adjuster です
     * @return DT_FieldAdjuster
     */
    private function getHourAdjuster() {
        static $adjuster;
        if (!isset($adjuster)) {
            $adjuster = new DT_FieldAdjuster(self::$HOUR, self::$DATE, 0, 23);
        }
        return $adjuster;
    }
    
    /**
     * 「分」フィールドを調整する Adjuster です
     * @return DT_FieldAdjuster
     */
    private function getMinuteAdjuster() {
        static $adjuster;
        if (!isset($adjuster)) {
            $adjuster = new DT_FieldAdjuster(self::$MINUTE, self::$HOUR, 0, 59);
        }
        return $adjuster;
    }
}
?>