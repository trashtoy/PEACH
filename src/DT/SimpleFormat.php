<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Format.php");
require_once(dirname(__FILE__) . "/../Util/load.php");

/**
 * Java の
 * {@link http://docs.oracle.com/javase/jp/6/api/java/text/SimpleDateFormat.html SimpleDateFormat}
 * と同じような使い勝手で, ユーザー定義の書式を扱うことができるクラスです.
 * 日付・時刻のパターンは {@link date() date} の一部を採用しています.
 * 
 * - Y: 年 (4桁)
 * - m: 月 (2桁固定)
 * - n: 月 (1～2桁)
 * - d: 日 (2桁固定)
 * - j: 日 (1～2桁)
 * - H: 時 (2桁固定)
 * - G: 時 (1～2桁)
 * - i: 分 (2桁固定)
 * - s: 秒 (2桁固定)
 * 
 * 以下は, このクラス独自の拡張パターンです.
 * 
 * - f: 分 (1～2桁)
 * - b: 秒 (1～2桁)
 * 
 * パターン文字列を繋げて記述する場合は (例: "Ymd_His" など)
 * 必ず固定長のパターンを使用してください.
 * 可変長 (n, j, G など) のパターンをパースする際に最短一致で計算するため,
 * パースに失敗する可能性があります.
 * 
 * パースする際に書式内に必要なパターンが含まれていなかった場合,
 * 年・月・日については現在の日付, 時・分・秒については 0 が適用されます.
 * 
 * PHP の date 関数の実装と同様に, 
 * バックスラッシュをつけることでパターン文字列が展開されるのを抑制することができます.
 * 
 * このクラスはイミュータブルです. 一つのオブジェクトを複数の箇所で使いまわすことが出来ます.
 * 
 * @package DT
 */
class DT_SimpleFormat implements DT_Format {
    /**
     * @var string
     */
    private $format;
    
    /**
     * @var array
     */
    private $context;
    
    public function __construct($format) {
        $format        = strval($format);
        $this->format  = $format;
        $this->context = $this->createContext($format);
    }
    
    public function getFormat() {
        return $this->format;
    }
    
    public function parseDate($format) {
        $d = DT_Date::now();
        return $d->setAll($this->interpret($format));
    }
    
    public function parseDatetime($format) {
        $d = DT_Date::now();
        return $d->toDatetime()->setAll($this->interpret($format));
    }
    
    public function parseTimestamp($format) {
        $d = DT_Date::now();
        return $d->toTimestamp()->setAll($this->interpret($format));
    }
    
    public function formatDate(DT_date $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    public function formatDatetime(DT_Datetime $d) {
        return $this->formatTimestamp($d->toTimestamp());
    }
    
    public function formatTimestamp(DT_Timestamp $d) {
        $patternList = $this->getPatternList();
        $result      = "";
        foreach ($this->context as $part) {
            $buf = array_key_exists($part, $patternList) ? $this->formatKey($d, $part) : stripslashes($part);
            $result .= $buf;
        }
        return $result;
    }
    
    private function getPatternList() {
        static $patterns = NULL;
        if (!isset($patterns)) {
            $fixed4   = "\\d{4}";
            $fixed2   = "\\d{2}";
            $var2     = "[1-5][0-9]|[0-9]";
            $varM     = "1[0-2]|[1-9]";
            $varD     = "3[0-1]|[1-2][0-9]|[0-9]";
            $varH     = "2[0-4]|1[0-9]|[0-9]";
            $patterns = array(
                "Y" => $fixed4,
                "m" => $fixed2,
                "n" => $varM,
                "d" => $fixed2,
                "j" => $varD,
                "H" => $fixed2,
                "G" => $varH,
                "i" => $fixed2,
                "f" => $var2,
                "s" => $fixed2,
                "b" => $var2,
            );
        }
        return $patterns;
    }
    
    private function getKey($pattern) {
        static $keyList = array(
            "year"   => array("Y"),
            "month"  => array("m", "n"),
            "date"   => array("d", "j"),
            "hour"   => array("H", "G"),
            "minute" => array("i", "f"),
            "second" => array("s", "b"),
        );
        foreach ($keyList as $key => $pList) {
            if (in_array($pattern, $pList)) {
                return $key;
            }
        }
        throw new Exception("Illegal pattern: " . $pattern);
    }
    
    private function formatKey(DT_Time $d, $key) {
        $year  = $d->get("year");
        $month = $d->get("month");
        $date  = $d->get("date");
        $hour  = $d->get("hour");
        $min   = $d->get("minute");
        $sec   = $d->get("second");
        
        switch ($key) {
            case "Y":
                return str_pad($year,  4, "0", STR_PAD_LEFT);
            case "m":
                return str_pad($month, 2, "0", STR_PAD_LEFT);
            case "n":
                return $month;
            case "d":
                return str_pad($date,  2, "0", STR_PAD_LEFT);
            case "j":
                return $date;
            case "H":
                return str_pad($hour,  2, "0", STR_PAD_LEFT);
            case "G":
                return $hour;
            case "i":
                return str_pad($min,   2, "0", STR_PAD_LEFT);
            case "f":
                return $min;
            case "s":
                return str_pad($sec,   2, "0", STR_PAD_LEFT);
            case "b":
                return $sec;
            default:
                throw new Exception("Illegal pattern: " . $key);
        }
    }
    
    /**
     * 
     * @param  string $format
     * @return array
     */
    private function createContext($format) {
        $patternList = $this->getPatternList();
        $result      = array();
        $current     = "";
        $escaped     = FALSE;
        for ($i = 0, $length = strlen($format); $i < $length; $i ++) {
            $chr = substr($format, $i, 1);
            if ($escaped) {
                $current .= $chr;
                $escaped = FALSE;
            }
            else if ($chr === "\\") {
                $current .= $chr;
                $escaped  = TRUE;
            }
            else if (array_key_exists($chr, $patternList)) {
                if (strlen($current)) {
                    $result[] = $current;
                    $current  = "";
                }
                $result[] = $chr;
            }
            else {
                $current .= $chr;
            }
        }
        if (strlen($current)) {
            $result[] = $current;
        }
        return $result;
    }
    
    private function interpret($input) {
        $patternList = $this->getPatternList();
        $result      = array();
        foreach ($this->context as $part) {
            if (array_key_exists($part, $patternList)) {
                $reg  = $patternList[$part];
                $test = preg_match("/^{$reg}/", $input, $matched);
                if ($test === FALSE) {
                    $this->throwFormatException($input, $this->format);
                }
                
                $key          = $this->getKey($part);
                $result[$key] = intval($matched[0]);
                $input        = substr($input, strlen($matched[0]));
            }
            else {
                $text   = stripslashes($part);
                $length = strlen($text);
                if (substr($input, 0, $length) !== $text) {
                    $this->throwFormatException($input, $this->format);
                }
                
                $input = substr($input, $length);
            }
        }
        
        return $result;
    }
    
    private function throwFormatException($format, $expected) {
        throw new Exception("Illegal format({$format}). Expected: {$expected}");
    }
}
?>
