<?php
/** @package DT */
/**
 * 文字列から時間オブジェクトへの変換と, 時間オブジェクトから文字列への変換をサポートするインタフェースです.
 * このインタフェースで定義されている各メソッドは, 以下の場所から使われることを想定しています.
 * ユーザー自身がこれらのメソッドを呼び出す機会は基本的にありません.
 * 
 * - {@link DT_Time::format()}
 * - {@link DT_Date::parse()}
 * - {@link DT_Datetime::parse()}
 * - {@link DT_Timestamp::parse()}
 * 
 * @package DT
 */
interface DT_Format {
    /**
     * 指定された文字列を解析して DATE 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return DT_Time        解析結果
     */
    public function parseDate($format);
    
    /**
     * 指定された文字列を解析して DATETIME 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return DT_Time        解析結果
     */
    public function parseDatetime($format);
    
    /**
     * 指定された文字列を解析して TIMESTAMP 型の時間オブジェクトに変換します.
     * 解析に失敗した場合は例外をスローします.
     * 
     * @param  string $format 解析対象の文字列
     * @return DT_Time        解析結果
     */
    public function parseTimestamp($format);
    
    /**
     * 指定された DT_Date オブジェクトを書式化します.
     * 
     * @param  DT_Date $d 書式化対象の時間オブジェクト
     * @return string     このフォーマットによる文字列表現
     */
    public function formatDate(DT_Date $d);
    
    /**
     * 指定された DT_Datetime オブジェクトを書式化します.
     * 
     * @param  DT_Datetime $d 書式化対象の時間オブジェクト
     * @return string         このフォーマットによる文字列表現
     */
    public function formatDatetime(DT_Datetime $d);
    
    /**
     * 指定された DT_Timestamp オブジェクトを書式化します.
     * 
     * @param  DT_Timestamp $d 書式化対象の時間オブジェクト
     * @return string          このフォーマットによる文字列表現
     */
    public function formatTimestamp(DT_Timestamp $d);
}
?>