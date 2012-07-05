<?php
/** @package DT */
/** @ignore */
require_once('DT/Datetime.php');
require_once('DT/W3CDatetimeFormat.php');
/**
 * 時刻を表すクラスです.
 * @package DT
 */
class DT_Timestamp extends DT_Datetime {
    /**
     * 秒を表す整数です.
     * @var int
     */
    private $second = 0;
    
    /**
     * 実行時の DT_Timestamp オブジェクトを返します.
     * @return DT_Timestamp
     */
    public static function now() {
        $year  = @date('Y');
        $month = @date('n');
        $date  = @date('d');
        $hour  = @date('H');
        $min   = @date('i');
        $sec   = @date('s');
        return new self($year, $month, $date, $hour, $min, $sec);
    }
    
    /**
     * @param string
     * @param DT_Format
     */
    public static function parse($text, DT_Format $format = NULL) {
        if (!isset($format)) {
            $format = DT_W3CDatetimeFormat::getDefault();
        }
        return $format->parseTimestamp($text);
    }
    
    /**
     * 与えられた時刻を表現する DT_Timestamp オブジェクトを構築します.
     * 
     * @param int $year  年
     * @param int $month 月
     * @param int $date  日
     * @param int $hour  時
     * @param int $min   分
     * @param int $sec   秒
     */
    public function __construct($year, $month, $date, $hour, $min, $sec) {
        $fields = new Util_ArrayMap();
        $fields->put(self::$YEAR,   intval($year));
        $fields->put(self::$MONTH,  intval($month));
        $fields->put(self::$DATE,   intval($date));
        $fields->put(self::$HOUR,   intval($hour));
        $fields->put(self::$MINUTE, intval($min));
        $fields->put(self::$SECOND, intval($sec));
        $this->init($fields);
    }
    
    /**
     * @ignore
     */
    protected function init(Util_Map $fields) {
        parent::init($fields);
        $this->second = $fields->get(self::$SECOND);
    }
    
    /**
     * この時間と指定された時間を比較します. 
     * 
     * @param  DT_Time 比較対象の時間
     * @return int     この時間のほうが過去の場合は負の値, 未来の場合は正の値, それ以外は 0
     * @ignore
     */
    protected function compareFields(DT_Time $time) {
        $c = parent::compareFields($time);
        if (!isset($c) || $c != 0) return $c;
        $className = __CLASS__;
        if ($time instanceof $className) {
            return $this->second - $time->second;
        }
        else {
            $s = $time->get("second");
            return ($s !== $this->second) ? $this->second - $s : 0;
        }
    }
    
    /**
     * 時刻の不整合を調整します.
     * @ignore
     */
    protected function adjust(Util_Map $fields) {
        parent::adjust($fields);
        $adjuster = $this->getAdjuster();
        $second = $fields->get("second");
        if ($second < 0) {
            $adjuster->moveDown($fields);
        }
        else if (59 < $second) {
            $adjuster->moveUp($fields);
        }
        else {
            return;
        }
        
        $this->adjust($fields);
    }
    
    /**
     * (non-PHPdoc)
     * @see DT/DT_Time#newInstance($fields)
     * @ignore
     */
    protected function newInstance(Util_Map $fields) {
        $year  = $fields->get(self::$YEAR);
        $month = $fields->get(self::$MONTH);
        $date  = $fields->get(self::$DATE);
        $hour  = $fields->get(self::$HOUR);
        $min   = $fields->get(self::$MINUTE);
        $sec   = $fields->get(self::$SECOND);
        return new self($year, $month, $date, $hour, $min, $sec);
    }
    
    /**
     * @ignore
     */
    protected function handleFormat(DT_Format $format) {
        return $format->formatTimestamp($this);
    }
    
    /**
     * (non-PHPdoc)
     * @see DT/DT_Datetime#formatTime()
     */
    public function formatTime() {
        $format = parent::formatTime();
        $sec = str_pad($this->second,  2, '0', STR_PAD_LEFT);
        return $format . ":" . $sec;
    }
    
    /**
     * このオブジェクトを DT_Timestamp 型にキャストします.
     * 返り値はこのオブジェクトのクローンです.
     *
     * @return DT_Timestamp このオブジェクトの Timestamp 表現
     */
    public function toTimestamp() {
        return new DT_Timestamp($this->year, $this->month, $this->date, $this->hour, $this->min, $this->sec);
    }
    
    private function getAdjuster() {
        static $adjuster;
        if (!isset($adjuster)) {
            $adjuster = new DT_FieldAdjuster(self::$SECOND, self::$MINUTE, 0, 59);
        }
        return $adjuster;
    }
}
?>