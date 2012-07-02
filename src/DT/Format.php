<?php
/** @package DT */
/**
 * 特定の書式に則った文字列から時間オブジェクトを構築するメソッドと, 
 * 時間オブジェクトを文字列に変換するメソッドを備えたインタフェースです.
 * @package DT
 */
interface DT_Format {
    /**
     * 指定された文字列を解析して Date オブジェクトに変換します.
     * @param  string $format 解析対象の文字列
     * @return DT_Date        解析結果
     */
    public function parseDate($format);
    
    /**
     * 指定された文字列を解析して Datetime オブジェクトに変換します.
     * @param  string $format 解析対象の文字列
     * @return DT_Datetime    解析結果
     */
    public function parseDatetime($format);
    
    /**
     * 指定された文字列を解析して Timestamp オブジェクトに変換します.
     * @param  string $format 解析対象の文字列
     * @return DT_Timestamp   解析結果
     */
    public function parseTimestamp($format);
    
    /**
     * 指定された Date オブジェクトを書式化します.
     * @param  DT_Date $d 書式化対象の時間オブジェクト
     * @return string     このフォーマットによる文字列表現
     */
    public function formatDate(DT_date $d);
    
    /**
     * 指定された Datetime オブジェクトを書式化します.
     * @param  DT_Datetime $d 書式化対象の時間オブジェクト
     * @return string         このフォーマットによる文字列表現
     */
    public function formatDatetime(DT_Datetime $d);
    
    /**
     * 指定された Timestamp オブジェクトを書式化します.
     * @param  DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string          このフォーマットによる文字列表現
     */
    public function formatTimestamp(DT_Timestamp $d);
}
?>