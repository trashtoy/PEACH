<?php
/** @package DT */
/** */
require_once("DT/Format.php");

/**
 * {@link http://www.w3.org/TR/NOTE-datetime W3CDTF} と時間オブジェクトの相互変換を行うフォーマットです.
 * 本来の W3CDTF は日付と時刻の間に "T" が入りますが (例: "YYYY-MM-DDThh:mm:ss") 
 * このモジュールでは SQL などで用いられる慣用表現 (例: "YYYY-MM-DD hh:mm:ss") も受理します.
 * 
 * @package DT
 */
class DT_W3CDatetimeFormat implements DT_Format {
    /**
     * タイムゾーンを付加するかどうかを表します.
     * @var bool
     */
    private $settingTz;
    
    /**
     * $settingTz が TRUE の場合に付加されるタイムゾーンの値です.
     * 単位は時です.
     * @var int
     */
    private $tz;
    
    /**
     * 新しいオブジェクトを構築します.
     * @param int $tz タイムゾーンの値(単位は時間). 指定しない場合はタイムゾーンは扱わない.
     */
    public function __construct($tz = NULL) {
        $this->settingTz = isset($tz);
        $this->tz        = intval($tz);
    }
    
    /**
     * デフォルトのフォーマットを返します.
     * @return DT_W3CDatetimeFormat
     */
    public static function getDefault() {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * "YYYY-MM-DD" 形式の文字列をパースします.
     * @return DT_Date
     * @see DT_Format::parseDate()
     */
    public function parseDate($format) {
        if (preg_match("/^[0-9]{4}([^0-9][0-9]{2}){2}/", $format)) {
            $year  = substr($format, 0, 4);
            $month = substr($format, 5, 2);
            $date  = substr($format, 8, 2);
            return new DT_Date($year, $month, $date);
        }
        else {
            $this->throwFormatException($format, "YYYY-MM-DD");
        }
    }
    
    /**
     * "YYYY-MM-DDThh:mm" 形式の文字列をパースします.
     * @return DT_Datetime
     * @see DT_Format::parseDatetime()
     */
    public function parseDatetime($format) {
        if (preg_match("/^[0-9]{4}([^0-9][0-9]{2}){2}[^0-9][0-9]{2}:[0-9]{2}/", $format)) {
            $year  = substr($format, 0,  4);
            $month = substr($format, 5,  2);
            $date  = substr($format, 8,  2);
            $hour  = substr($format, 11, 2);
            $min   = substr($format, 14, 2);
            return new DT_Datetime($year, $month, $date, $hour, $min);
        }
        else {
            $this->throwFormatException($format, "YYYY-MM-DD hh:mm");
        }
    }
    
    /**
     * "YYYY-MM-DDThh:mm:ss" 形式の文字列をパースします.
     * @return DT_Timestamp
     * @see DT_Format::parseTimestamp()
     */
    public function parseTimestamp($format) {
        if (preg_match("/^[0-9]{4}([^0-9][0-9]{2}){2}[^0-9]([0-9]{2}:){2}[0-9]{2}/", $format)) {
            $year  = substr($format, 0,  4);
            $month = substr($format, 5,  2);
            $date  = substr($format, 8,  2);
            $hour  = substr($format, 11, 2);
            $min   = substr($format, 14, 2);
            $sec   = substr($format, 17, 2);
            return new DT_Timestamp($year, $month, $date, $hour, $min, $sec);
        }
        else {
            $this->throwFormatException($format, "YYYY-MM-DD hh:mm:ss");
        }
    }
    
    /**
     * "YYYY-MM-DD" 形式の文字列を返します.
     * @return string
     * @see    DT_Format::formatDate()
     */
    public function formatDate(DT_Date $d) {
        return $d->__toString();
    }
    
    /**
     * "YYYY-MM-DDThh:mm" 形式の文字列を返します.
     * @return string
     * @see    DT_Format::formatDatetime()
     */
    public function formatDatetime(DT_Datetime $d) {
        return $this->formatDate($d) . "T" . $d->formatTime() . $this->formatTimezone();
    }
    
    /**
     * "YYYY-MM-DDThh:mm:ss" 形式の文字列を返します.
     * @return string
     * @see    DT_Format::formatTimestamp()
     */
    public function formatTimestamp(DT_Timestamp $d) {
        return $this->formatDatetime($d);
    }
    
    private function throwFormatException($format, $expected) {
        throw new Exception("Illegal format({$format}). Expected: {$expected}");
    }
    
    /**
     * タイムゾーンを書式化します.
     */
    private function formatTimezone() {
        if (!$this->settingTz) {
            return '';
        }
        $tz     = $this->tz;
        $format = (0 < $tz) ? '+' : '-';
        $tz     = abs($tz);
        $hour   = intval($tz);
        $min    = intval(($tz - $hour) * 60);
        $format .= str_pad($hour, 2, '0', STR_PAD_LEFT);
        $format .= ':';
        $format .= str_pad($min,  2, '0', STR_PAD_LEFT);
        return $format;
    }
}
?>