<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Format.php");

/**
 * Unix time ({@link time() time()} の返り値や {@link date() date()} の引数として使用される整数) 
 * と時間オブジェクトの相互変換を行うクラスです.
 * 
 * このクラスはシングルトンです. {@link DT_HttpDateFormat::getInstance() getInstance()}
 * からオブジェクトを取得してください.
 * 
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
        static $instance = NULL;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * 指定されたタイムスタンプを DT_Date に変換します.
     * 
     * @param  string  タイムスタンプ
     * @return DT_Date 変換結果
     */
    public function parseDate($format) {
        $time  = intval($format);
        $year  = @date('Y', $time);
        $month = @date('n', $time);
        $date  = @date('d', $time);
        return new DT_Date($year, $month, $date);
    }
    
    /**
     * 指定されたタイムスタンプを DT_Datetime に変換します.
     * 
     * @param  string      タイムスタンプ
     * @return DT_Datetime 変換結果
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
     * 指定されたタイムスタンプを DT_Timestamp に変換します.
     * @param  string       タイムスタンプ
     * @return DT_Timestamp 変換結果
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
     * {@link DT_HttpDateFormat::formatTimestamp() formatTimestamp()}
     * を実行した結果を返します.
     * 
     * @param  DT_Date $d
     * @return string  指定された日付の 0 時 0 分 0 秒のタイムスタンプ
     */
    public function formatDate(DT_Date $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定されたオブジェクトを DT_Timestamp 型にキャストして
     * {@link DT_HttpDateFormat::formatTimestamp() formatTimestamp()}
     * を実行した結果を返します.
     * 
     * @param  DT_Datetime $d
     * @return string      指定された時刻の 0 秒のタイムスタンプ
     */
    public function formatDatetime(DT_Datetime $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    /**
     * 指定された時刻をタイムスタンプに変換します.
     * 
     * このメソッドは、引数の DT_Timestamp オブジェクトが持つ各フィールド
     * (年月日・時分秒) の値から {@link mktime() mktime()} を行い, 
     * その結果を返り値とします.
     * 
     * ただし, 返り値が string 型となることに注意してください.
     * 
     * @param  DT_Timestamp $d
     * @return string       指定された時刻のタイムスタンプ
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