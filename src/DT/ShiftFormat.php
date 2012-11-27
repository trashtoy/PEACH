<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/FormatWrapper.php");

/**
 * システム内部の時差とフォーマットの時差を自動で調整するためのフォーマットです.
 * 例えば「閲覧しているユーザーのタイムゾーンに合わせて表示する時刻を調整したい」
 * といったケースでこのフォーマットを利用します.
 * 
 * 以下に動作例を示します.
 * 
 * <code>
 * // ※システムのタイムゾーンが Asia/Tokyo (UTC+9) に設定されているものとします
 *  
 * $f1 = new DT_SimpleFormat("Y.m.d H:i:s");
 * $f2 = new DT_ShiftFormat($f1, 0); // 表示用の時刻を GMT とします
 * 
 * // 2012-05-20 22:30:45 の DT_Timestamp オブジェクトを返す
 * var_dump(DT_Timestamp::parse("2012.05.20 22:30:45", $f1));
 * // 2012-05-21 07:30:45 の DT_Timestamp オブジェクトを返す (GMT から UTC+9 に変換される)
 * var_dump(DT_Timestamp::parse("2012.05.21 22:30:45", $f2));
 * 
 * $d = new DT_Timestamp(2012, 1, 1, 0, 0, 0);
 * var_dump($d->format($f1)); // 2012.01.01 00:00:00
 * var_dump($d->format($f2)); // 2011.12.31 15:00:00 (UTC+9 から GMT に変換)
 * </code>
 * 
 * このクラスの parseDate と formatDate は時差の変換を行いません.
 * オリジナルの実行結果をそのまま返します.
 * 
 * @package DT
 */
class DT_ShiftFormat extends DT_FormatWrapper {
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
     * フォーマットの時差とシステム時刻の時差を指定して, 
     * 新しい ShiftFormat を構築します.
     * 引数の単位は分です. UTC+1 以降の場合は負の値,
     * UTC-1 以前の場合は正の値を指定してください.
     * 
     * @param DT_Format $original  調整対象のフォーマット
     * @param type $externalOffset フォーマットの時差 (単位は分)
     * @param type $internalOffset システム時刻の時差 (単位は分, 省略した場合はシステム設定の値を使用)
     */
    public function __construct(DT_Format $original, $externalOffset, $internalOffset = NULL) {
        parent::__construct($original);
        $this->externalOffset = DT_Util::cleanTimeZoneOffset($externalOffset);
        $this->internalOffset = DT_Util::cleanTimeZoneOffset($internalOffset);
    }
    
    /**
     * オリジナルの parseDatetime で得られた結果をシステム時刻に変換して返します.
     * @param  string $format 解析対象の文字列 
     * @return DT_Time 変換結果
     */
    public function parseDatetime($format) {
        return $this->adjustFromParse(parent::parseDatetime($format));
    }
    
    /**
     * オリジナルの parseTimestamp で得られた結果をシステム時刻に変換して返します.
     * @param  string $format 解析対象の文字列
     * @return DT_Time 変換結果
     */
    public function parseTimestamp($format) {
        return $this->adjustFromParse(parent::parseTimestamp($format));
    }
    
    /**
     * parse 系メソッドから呼ばれる変換処理です.
     * @param  DT_Time $d parse された時間オブジェクト
     * @return DT_Time 表示と内部の時差だけ「分」を移動させた時間オブジェクト
     */
    private function adjustFromParse(DT_Time $d) {
        return $d->add("minute", $this->externalOffset - $this->internalOffset);
    }
    
    /**
     * 変換対象の時間オブジェクトを表示用のタイムゾーンに変換してから,
     * オリジナルの formatDatetime を実行します.
     * @param  DT_Datetime $d 変換対象の時間オブジェクト
     * @return string 変換結果
     */
    public function formatDatetime(DT_Datetime $d) {
        return parent::formatDatetime($this->adjustFromFormat($d));
    }
    
    /**
     * 変換対象の時間オブジェクトを表示用のタイムゾーンに変換してから,
     * オリジナルの formatTimestamp を実行します.
     * @param  DT_Timestamp $d 変換対象の時間オブジェクト
     * @return string 変換結果
     */
    public function formatTimestamp(DT_Timestamp $d) {
        return parent::formatTimestamp($this->adjustFromFormat($d));
    }
    
    /**
     * format 系メソッドから呼ばれる変換処理です.
     * @param  DT_Time $d 変換対象の時間オブジェクト
     * @return DT_Time 表示と内部の時差だけ「分」を移動させた時間オブジェクト
     */
    private function adjustFromFormat(DT_Time $d) {
        return $d->add("minute", $this->internalOffset - $this->externalOffset);
    }
}
?>