<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Format.php");

/**
 * HTTP-Date の書式を扱うクラスです.
 * 参考文献:
 * {@link http://www.arielworks.net/articles/2004/0125a モジュール版PHPで「If-Modified-Since」に対応する}
 * 
 * @package DT
 */
class DT_HttpDateFormat implements DT_Format {
    /**
     * このクラスは getInstance() からインスタンス化します.
     */
    private function __construct() {}
    
    /**
     * このクラスのインスタンスを取得します.
     * @return DT_HttpDateFormat
     */
    public static function getInstance() {
        static $instance = NULL;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * {@link DT_HttpDateFormat::parseTimestamp() parseTimestamp()} の実行結果を DT_Date にキャストします.
     * @return DT_Date
     */
    public function parseDate($format) {
        return $this->parseTimestamp($format)->toDate();
    }
    
    /**
     * {@link DT_HttpDateFormat::parseTimestamp() parseTimestamp()} の実行結果を DT_Datetime にキャストします.
     * @return DT_Datetime
     */
    public function parseDatetime($format) {
        return $this->parseTimestamp($format)->toDatetime();
    }
    
    /**
     * HTTP-date 形式のフォーマットを DT_Timestamp に変換します.
     * @param  string       HTTP-date 形式の文字列
     * @return DT_Timestamp 変換結果
     */
    public function parseTimestamp($format) {
        $temp_date = array();
        if (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun), ([0-3][0-9]) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-9]{4}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[5];
            $date["minute"] = $temp_date[6];
            $date["second"] = $temp_date[7];
            $date["month"]  = $this->parseMonthDescription($temp_date[3]);
            $date["day"]    = $temp_date[2];
            $date["year"]   = $temp_date[4];
        }
        else if (preg_match("/^(Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday), ([0-3][0-9])-(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)-([0-9]{2}) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) GMT$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[5];
            $date["minute"] = $temp_date[6];
            $date["second"] = $temp_date[7];
            $date["month"]  = $this->parseMonthDescription($temp_date[3]);
            $date["day"]    = $temp_date[2];
            $date["year"]   = $this->getFullYear($temp_date[4]);
        }
        else if (preg_match("/^(Mon|Tue|Wed|Thu|Fri|Sat|Sun) (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) ([0-3 ][0-9]) ([0-2][0-9]):([0-5][0-9]):([0-5][0-9]) ([0-9]{4})$/", $format, $temp_date)) {
            $date["hour"]   = $temp_date[4];
            $date["minute"] = $temp_date[5];
            $date["second"] = $temp_date[6];
            $date["month"]  = $this->parseMonthDescription($temp_date[2]);
            // 日が1桁の場合先、半角スペースを0に置換
            $date["day"]    = str_replace(" ", "0", $temp_date[3]);
            // 定義済みの月の名前を数字に変換する
            $date["year"]   = $temp_date[7];
        } 
        else {
            $this->throwFormatException($format);
        }
        
        return new DT_Timestamp($date["year"], $date["month"], $date["day"], $date["hour"], $date["minute"], $date["second"]);
    }
    
    /**
     * この時間オブジェクトを DT_Datetime にキャストして Http-date を書式化します.
     * @param  DT_Date
     * @return string
     */
    public function formatDate(DT_Date $d) {
        return $this->formatDatetime($d->toDatetime());
    }
    
    /**
     * この時刻の Http-date 表現を返します.
     * このメソッドは, 引数の時刻を GMT とみなして書式化します.
     * タイムゾーンに応じた自動変換などは行わないため,
     * 必要に応じて {@link DT_Time::add() add} メソッドなどで時刻を調整してください.
     * 
     * @param  DT_Datetime
     * @return string
     */
    public function formatDatetime(DT_Datetime $d) {
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
     * この時刻の Http-date 表現を返します.
     */
    public function formatTimestamp(DT_Timestamp $d) {
        return $this->formatDatetime($d);
    }
    
    private function throwFormatException($format) {
        throw new Exception("Illegal format({$format}). HTTP-date format required.");
    }
    
    /**
     * 月の略称一覧です
     * @return array
     */
    private function getMonthMapping() {
        static $mapping = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
        return $mapping;
    }
    
    /**
     * 月の略称を書式化します.
     * @return string
     */
    private function getMonthDescription($month) {
        $mapping = $this->getMonthMapping();
        return $mapping[$month - 1];
    }
    
    /**
     * 月の略称を月に変換します.
     * @param  string $mon
     * @return int
     */
    private function parseMonthDescription($mon) {
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
    private function getFullYear($y) {
        $currentYear = intval(date("Y"));
        $century     = intval($currentYear / 100);
        $smallY      = $currentYear % 100;
        $resultC     = ($smallY < $y) ? $century - 1 : $century;
        return $resultC * 100 + $y;
    }
    
    /**
     * 曜日の略称を(ry
     * @param  int    曜日の値
     * @return string 曜日の略称
     */
    private function getDayDescription($day) {
        switch ($day) {
        case 0: return 'Sun';
        case 1: return 'Mon';
        case 2: return 'Tue';
        case 3: return 'Wed';
        case 4: return 'Thu';
        case 5: return 'Fri';
        case 6: return 'Sat';
        default:
            throw new Exception('');
        }
    }
}
?>