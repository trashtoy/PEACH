<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/Format.php");
/**
 * 既存の DT_Format オブジェクトを機能拡張するためのラッパークラスです.
 * このクラスは Decorator パターンで設計されています.
 * @package DT
 */
class DT_FormatWrapper implements DT_Format {
    /**
     * ラップする DT_Format オブジェクトです.
     * @var DT_Format
     */
    private $original;
    
    /**
     * 指定された DT_Format オブジェクトをラップする FormatWrapper を構築します.
     * @param DT_Format $original ラップ対象のオブジェクト
     */
    public function __construct(DT_Format $original) {
        $this->original = $original;
    }
    
    /**
     * ラップ対象の DT_Format オブジェクトを返します.
     * @return DT_Format ラップ対象のオブジェクト
     */
    public function getOriginal() {
        return $this->original;
    }
    
    /**
     * ラップ対象のオブジェクトの formatDate メソッドを実行します.
     * @param  DT_Date $d
     * @return string オリジナルの formatDate の結果
     */
    public function formatDate(DT_Date $d) {
        return $this->original->formatDate($d);
    }
    
    /**
     * ラップ対象のオブジェクトの formatDatetime メソッドを実行します.
     * @param  DT_Datetime $d
     * @return string オリジナルの formatDatetime の結果
     */
    public function formatDatetime(DT_Datetime $d) {
        return $this->original->formatDatetime($d);
    }
    
    /**
     * ラップ対象のオブジェクトの formatTimestamp メソッドを実行します.
     * @param  DT_Timestamp $d
     * @return string オリジナルの formatTimestamp の結果
     */
    public function formatTimestamp(DT_Timestamp $d) {
        return $this->original->formatTimestamp($d);
    }
    
    /**
     * ラップ対象のオブジェクトの parseDate メソッドを実行します.
     * @param  string $format
     * @return DT_Time オリジナルの parseDate の結果
     */
    public function parseDate($format) {
        return $this->original->parseDate($format);
    }
    
    /**
     * ラップ対象のオブジェクトの parseDatetime メソッドを実行します.
     * @param  string $format
     * @return DT_Time オリジナルの parseDatetime の結果
     */
    public function parseDatetime($format) {
        return $this->original->parseDatetime($format);
    }
    
    /**
     * ラップ対象のオブジェクトの parseTimestamp メソッドを実行します.
     * @param  string $format
     * @return DT_Time オリジナルの parseTimestamp の結果
     */
    public function parseTimestamp($format) {
        return $this->original->parseTimestamp($format);
    }
}
?>