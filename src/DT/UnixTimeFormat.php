<?php
/** @package DT */
/** */
require_once('DT/Format.php');
/**
 * Unix time ({@link time() time()} の返り値や {@link date() date()} の引数として使用される整数) 
 * と時間オブジェクトの相互変換を行うクラスです.
 * @package DT
 */
class DT_UnixTimeFormat implements DT_Format {
    /**
     * このクラスは getInstance() からインスタンス化します.
     */
    private function __construct() {}
    
    /**
     * このクラスのインスタンスを取得します.
     * @return DT_UnixTimeFormat
     */
    public static function getInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * @see DT_Format::parseDate()
     * @param  string $format
     * @return DT_Date
     */
    public function parseDate($format) {
        $time  = intval($format);
        $year  = @date('Y', $time);
        $month = @date('n', $time);
        $date  = @date('d', $time);
        return new DT_Date($year, $month, $date);
    }
    
    /**
     * @see DT_Format::parseDatetime()
     * @param  string $format
     * @return DT_Datetime
     */
    public function parseDatetime($format) {
        $time  = intval($format);
        $year  = @date('Y', $time);
        $month = @date('n', $time);
        $date  = @date('d', $time);
        $hour  = @date('H', $time);
        $min   = @date('i', $time);
        return new DT_Datetime($year, $month, $date, $hour, $min);
    }
    
    /**
     * @see DT_Format::parseTimestamp()
     * @param  string $format
     * @return DT_Timestamp
     */
    public function parseTimestamp($format) {
        $time  = intval($format);
        $year  = @date('Y', $time);
        $month = @date('n', $time);
        $date  = @date('d', $time);
        $hour  = @date('H', $time);
        $min   = @date('i', $time);
        $sec   = @date('s', $time);
        return new DT_Timestamp($year, $month, $date, $hour, $min, $sec);
    }
    
    /**
     * 指定されたオブジェクトを DT_Timestamp 型にキャストして
     * formatTimestamp() を実行した結果を返します.
     * 
     * @param  DT_Date $d
     * @return string
     * @see DT_UnixTimeFormat::formatTimestamp()
     */
    public function formatDate(DT_Date $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定されたオブジェクトを DT_Timestamp 型にキャストして
     * formatTimestamp() を実行した結果を返します.
     * 
     * @param  DT_Datetime $d
     * @return string
     * @see DT_UnixTimeFormat::formatTimestamp()
     */
    public function formatDatetime(DT_Datetime $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定されたタイムスタンプを書式化します.
     * このメソッドは、引数の Timestamp が持つ各フィールド (年月日・時分秒) の値から {@link mktime() mktime()} を行い, 
     * その int 値を結果とします.
     * ただし, 返り値が string 型となることに注意してください.
     * 
     * @param  DT_Timestamp $d
     * @return string
     */
    public function formatTimestamp(DT_Timestamp $d) {
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