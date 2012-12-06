<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Format.php");
require_once(dirname(__FILE__) . "/../Util/load.php");

/**
 * {@link http://www.w3.org/TR/NOTE-datetime W3CDTF} と時間オブジェクトの相互変換を行うフォーマットです.
 * 本来の W3CDTF は日付と時刻の間に "T" が入りますが (例: "YYYY-MM-DDThh:mm:ss") 
 * このクラスは SQL などで用いられる慣用表現 (例: "YYYY-MM-DD hh:mm:ss") のパースもできます.
 * 
 * parse メソッドは, 文字列の先頭が W3CDTF の書式にマッチするかどうかで妥当性をチェックします.
 * "2012-05-21T07:30:00" のような文字列は
 * parseDate, parseDatetime, parseTimestamp のすべてのメソッドで受け付けますが,
 * "2012-05-21" のような文字列は parseDate だけしか受け付けません.
 * 
 * このクラスはタイムゾーンを適切に扱うことが出来ます.
 * parse 系メソッドでは, 時間オブジェクトに変換する際に内部のタイムゾーンに合わせて時刻を調整します.
 * 例えばシステム時刻が UTC+9 として, GMT の時間文字列 "2012-05-20T22:30Z" を parse した場合
 * 9 時間進ませた時間オブジェクト (new DT_Datetime(2012, 5, 21, 7, 30) に等価) を生成します.
 * 
 * 時間オブジェクトを書式化する際は, 出力用の時差に合わせた文字列を生成します.
 * 例えば内部のタイムゾーンが UTC+9, 出力用のタイムゾーンが UTC+5 に設定されているものとします.
 * この場合, 内部のタイムゾーンと出力用のタイムゾーンの時差が -4 時間となるため,
 * new DT_Datetime(2012, 5, 21, 7, 30) の時間オブジェクトを書式化した結果は
 * "2012-05-21T03:30+5:00" となります.
 * 
 * @package DT
 */
class DT_W3cDatetimeFormat implements DT_Format {
    /**
     * タイムゾーンを扱うのみ TRUE となります
     * @var bool
     */
    private $usingTz;
    
    /**
     * システム時刻の時差です (単位は分)
     * @var int
     */
    private $internalOffset;
    
    /**
     * フォーマットの時差です (単位は分)
     * @var type 
     */
    private $externalOffset;
    
    /**
     * 日付文字列のパターンです.
     * "YYYY-MM-DD" のような形式にマッチします.
     * 
     * @var string
     */
    private static $datePattern = "[0-9]{4}[^0-9][0-9]{2}[^0-9][0-9]{2}";
    
    /**
     * タイムゾーンの文字列のパターンです. 以下の形式にマッチします.
     * 
     * - "Z"
     * - "-hh:mm"
     * - "+hh:mm"
     * - 空文字列
     * 
     * @var string
     */
    private static $timeZonePattern = "(Z|[\\-\\+][0-9]{2}:[0-9]{2})?";
    
    /**
     * 指定されたタイムゾーンの条件で, 新しいフォーマットを構築します.
     * タイムゾーンを扱わない場合は, コンストラクタの代わりに
     * {@link DT_W3cDatetimeFormat::getInstance()} を使って下さい.
     * 
     * 引数 $externalOffset を指定することで, 
     * 書式化する際のタイムゾーンの値を設定することが出来ます.
     * (デフォルトはシステム時刻のタイムゾーンとなります)
     * さらに $internalOffset を指定することで,
     * システム時刻のタイムゾーンとして任意の値を指定することも出来ます.
     * 
     * $externalOffset と $internalOffset を省略した場合, デフォルト値としてシステム時刻の設定
     * ({@link DT_Util::getTimeZoneOffset()}) と同じ値が指定されます.
     * UTC+1 以上の時差を設定する場合は負の値,
     * UTC-1 以下の時差を設定する場合は正の値を指定してください.
     * 
     * 以下に, $externalOffset と $internalOffset の組み合わせによる動作例を示します.
     * 
     * <code>
     * // 書式・システム時刻共に UTC+9
     * $f1 = new DT_W3cDatetimeFormat(-540, -540);
     * 
     * // 書式は GMT, システム時刻は UTC+9
     * $f2 = new DT_W3cDatetimeFormat(0,    -540);
     * 
     * // 書式は UTC+9, システム時刻は GMT
     * $f3 = new DT_W3cDatetimeFormat(-540,    0);
     * 
     * // 2012-05-21T07:30 の時間オブジェクトを生成
     * $d = new DT_Datetime(2012, 5, 21, 7, 30);
     * var_dump($d->format($f1)); // "2012-05-21T07:30+09:00"
     * var_dump($d->format($f2)); // "2012-05-20T22:30Z"      (UTC+9 の時刻を GMT として書式化)
     * var_dump($d->format($f3)); // "2012-05-21T16:30+09:00" (GMT の時刻を UTC+9 として書式化)
     * </code>
     * 
     * @param int  $externalOffset フォーマットの時差 (単位は分, 省略した場合はシステム設定の値を使用)
     * @param int  $internalOffset システム時刻の時差 (単位は分, 省略した場合はシステム設定の値を使用)
     */
    public function __construct($externalOffset = NULL, $internalOffset = NULL) {
        if ($externalOffset === FALSE) {
            $this->usingTz        = FALSE;
            $this->externalOffset = NULL;
            $this->internalOffset = NULL;
        }
        else {
            $this->usingTz        = TRUE;
            $this->externalOffset = DT_Util::cleanTimeZoneOffset($externalOffset);
            $this->internalOffset = DT_Util::cleanTimeZoneOffset($internalOffset);
        }
    }
    
    /**
     * 時差の設定値を調整して返します.
     * もしも -23:30 以前の時差が設定された場合は -23:30 に切り上げられます.
     * もしも +23:30 以後の時差が設定された場合は +23:30 に切り下げられます.
     * NULL が指定された場合はシステム時刻の時差を設定します.
     * 
     * @param  int $offset
     * @return int
     */
    private function initOffset($offset) {
        return isset($offset) ? 
            Util_Values::intValue($offset, -1410, 1410) : // -23:30 から +23:30 まで
            DT_Util::getTimeZoneOffset();
    }
    
    /**
     * デフォルトのインスタンスを返します.
     * このメソッドから生成されたオブジェクトは, parse の際にタイムゾーンを一切考慮しません.
     * また, 書式化する際にタイムゾーン文字列を付与しません.
     * 
     * @return DT_W3cDatetimeFormat タイムゾーンに対応しないインスタンス
     */
    public static function getInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new self(FALSE);
        }
        return $instance;
    }
    
    /**
     * "YYYY-MM-DD" 形式の文字列をパースします.
     * 
     * @return DT_Date
     * @see DT_Format::parseDate()
     */
    public function parseDate($format) {
        $dp = self::$datePattern;
        if (preg_match("/^{$dp}/", $format)) {
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
     * 文字列の末尾にタイムゾーン (+hh:mm や -hh:mm など) を含む場合は, システム時刻への変換を行います.
     * 
     * @return DT_Datetime
     * @see DT_Format::parseDatetime()
     */
    public function parseDatetime($format) {
        $dp  = self::$datePattern;
        $tzp = self::$timeZonePattern;
        if (!preg_match("/^{$dp}[^0-9][0-9]{2}:[0-9]{2}{$tzp}/", $format, $result)) {
            $this->throwFormatException($format, "YYYY-MM-DD hh:mm[timezone]");
        }
        
        $year   = substr($format, 0,  4);
        $month  = substr($format, 5,  2);
        $date   = substr($format, 8,  2);
        $hour   = substr($format, 11, 2);
        $min    = substr($format, 14, 2);
        $obj    = new DT_Datetime($year, $month, $date, $hour, $min);
        return ($this->usingTz && isset($result[1])) ?
            $this->adjustFromParse($obj, $result[1]) : $obj;
    }
    
    /**
     * "YYYY-MM-DDThh:mm:ss" 形式の文字列をパースします.
     * @return DT_Timestamp
     * @see DT_Format::parseTimestamp()
     */
    public function parseTimestamp($format) {
        $dp  = self::$datePattern;
        $tzp = self::$timeZonePattern;
        if (!preg_match("/^{$dp}[^0-9][0-9]{2}:[0-9]{2}:[0-9]{2}{$tzp}/", $format, $result)) {
            $this->throwFormatException($format, "YYYY-MM-DD hh:mm:ss");
        }
        
        $year   = substr($format, 0,  4);
        $month  = substr($format, 5,  2);
        $date   = substr($format, 8,  2);
        $hour   = substr($format, 11, 2);
        $min    = substr($format, 14, 2);
        $sec    = substr($format, 17, 2);
        $obj    = new DT_Timestamp($year, $month, $date, $hour, $min, $sec);
        return ($this->usingTz && isset($result[1])) ?
            $this->adjustFromParse($obj, $result[1]) : $obj;
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
        $a = $this->adjustFromFormat($d, FALSE);
        return $this->formatDate($a->toDate()) . "T" . $a->formatTime() . $this->formatTimezone();
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
     * タイムゾーン文字列をパースして, 時差を分単位で返します.
     * NULL が指定された場合 (タイムゾーン文字列がなかった場合),
     * このオブジェクトに設定されている externalOffset の値を返します.
     * 
     * @param  string $tz NULL, "Z", または "+h:mm", "-h:mm" 形式の文字列
     * @return int
     */
    private function parseTimezone($tz) {
        if ($tz === "Z") {
            return 0;
        }
        else {
            $coronIndex = strpos($tz, ":");
            $sign       = substr($tz, 0, 1);
            $hour       = substr($tz, 1, $coronIndex - 1);
            $minute     = substr($tz, $coronIndex + 1);
            $offset     = $hour * 60 + $minute;
            return ($sign === "-") ? $offset : - $offset;
        }
    }
    
    /**
     * フォーマットのタイムゾーンと, 時間オブジェクトのタイムゾーンを元に
     * 指定された時間オブジェクトの補正処理を行います.
     * 
     * @param  DT_Datetime $d
     * @param  string      $tz      タイムゾーン文字列
     * @param  bool        $toParse parse の場合は TRUE, format の場合は FALSE
     * @return DT_Datetime 補正結果の時間オブジェクト
     */
    private function adjustFromParse(DT_Datetime $d, $tz) {
        $externalOffset = $this->parseTimezone($tz);
        return $d->add("minute", $externalOffset - $this->internalOffset);
    }
    
    private function adjustFromFormat(DT_Datetime $d) {
        return $this->usingTz ? $d->add("minute", $this->internalOffset - $this->externalOffset) : $d;
    }
    
    /**
     * タイムゾーンを書式化します.
     */
    private function formatTimezone() {
        if (!$this->usingTz) {
            return "";
        }
        if ($this->externalOffset === 0) {
            return "Z";
        }
        
        $ext    = $this->externalOffset;
        $format = (0 < $ext) ? "-" : "+";
        $offset = abs($ext);
        $hour   = intval($offset / 60);
        $minute = intval($offset % 60);
        $format .= str_pad($hour,   2, '0', STR_PAD_LEFT);
        $format .= ':';
        $format .= str_pad($minute, 2, '0', STR_PAD_LEFT);
        return $format;
    }
}
?>