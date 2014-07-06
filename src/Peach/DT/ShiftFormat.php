<?php
/*
 * Copyright (c) 2013 @trashtoy
 * https://github.com/trashtoy/
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/** @package DT */
/**
 * システム内部の時差とフォーマットの時差を自動で調整するためのフォーマットです.
 * 「閲覧しているユーザーのタイムゾーンに合わせて表示する時刻を調整したい」
 * といったケースで, 既存のフォーマットにかぶせる形で利用します.
 * 
 * サンプルとして, 日本語のサイトがニューヨークのサーバーで管理されているというシナリオを考えます.
 * サーバー側ではタイムゾーンを America/New_York (UTC-5) として時間の管理をしているが,
 * ブラウザに出力する際は Asia/Tokyo (UTC+9) のタイムゾーンで表示させたいとします.
 * 
 * <code>
 * // php.ini で timezone = America/New_York が設定されているものとする
 * 
 * // 表示用の時刻を UTC+9 とする (-540 分のオフセットを指定する)
 * $f = new Peach_DT_ShiftFormat(new Peach_DT_SimpleFormat("Y/m/d H:i:s"), -540);
 * 
 * // 日本のユーザーが入力した時刻をサーバー内部の時刻に変換する
 * $d = Peach_DT_Timestamp::parse("2012/05/21 07:30:45", $f);
 * echo $d; // "2012-05-20 17:30:45" (UTC+9 から UTC-5 に変換)
 * 
 * // サーバで管理されている時刻を日本のユーザー向けに変換する
 * $d = new Peach_DT_Timestamp(2012, 1, 1, 0, 0, 0);
 * echo $d->format($f); // "2012/01/01 14:00:00" (UTC-5 から UTC+9 に変換)
 * </code>
 * 
 * このクラスの parseDate と formatDate は時差の変換を行いません.
 * オリジナルの実行結果をそのまま返します.
 * 
 * ちなみに {@link Peach_DT_W3cDatetimeFormat} と {@link Peach_DT_HttpDateFormat}
 * に関しては, 自身でタイムゾーンの変換機能をサポートしているため,
 * このクラスを使う必要はありません.
 * 
 * @package DT
 */
class Peach_DT_ShiftFormat extends Peach_DT_FormatWrapper
{
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
     * @param Peach_DT_Format $original  調整対象のフォーマット
     * @param type $externalOffset フォーマットの時差 (単位は分)
     * @param type $internalOffset システム時刻の時差 (単位は分, 省略した場合はシステム設定の値を使用)
     */
    public function __construct(Peach_DT_Format $original, $externalOffset, $internalOffset = null)
    {
        parent::__construct($original);
        $this->externalOffset = Peach_DT_Util::cleanTimeZoneOffset($externalOffset);
        $this->internalOffset = Peach_DT_Util::cleanTimeZoneOffset($internalOffset);
    }

    /**
     * オリジナルの parseDatetime で得られた結果をシステム時刻に変換して返します.
     * @param  string $format 解析対象の文字列 
     * @return Peach_DT_Time 変換結果
     */
    public function parseDatetime($format)
    {
        return $this->adjustFromParse(parent::parseDatetime($format));
    }

    /**
     * オリジナルの parseTimestamp で得られた結果をシステム時刻に変換して返します.
     * @param  string $format 解析対象の文字列
     * @return Peach_DT_Time 変換結果
     */
    public function parseTimestamp($format)
    {
        return $this->adjustFromParse(parent::parseTimestamp($format));
    }

    /**
     * parse 系メソッドから呼ばれる変換処理です.
     * @param  Peach_DT_Time $d parse された時間オブジェクト
     * @return Peach_DT_Time 表示と内部の時差だけ「分」を移動させた時間オブジェクト
     */
    private function adjustFromParse(Peach_DT_Time $d)
    {
        return $d->add("minute", $this->externalOffset - $this->internalOffset);
    }

    /**
     * 変換対象の時間オブジェクトを表示用のタイムゾーンに変換してから,
     * オリジナルの formatDatetime を実行します.
     * @param  Peach_DT_Datetime $d 変換対象の時間オブジェクト
     * @return string 変換結果
     */
    public function formatDatetime(Peach_DT_Datetime $d)
    {
        return parent::formatDatetime($this->adjustFromFormat($d));
    }

    /**
     * 変換対象の時間オブジェクトを表示用のタイムゾーンに変換してから,
     * オリジナルの formatTimestamp を実行します.
     * @param  Peach_DT_Timestamp $d 変換対象の時間オブジェクト
     * @return string 変換結果
     */
    public function formatTimestamp(Peach_DT_Timestamp $d)
    {
        return parent::formatTimestamp($this->adjustFromFormat($d));
    }

    /**
     * format 系メソッドから呼ばれる変換処理です.
     * @param  Peach_DT_Time $d 変換対象の時間オブジェクト
     * @return Peach_DT_Time 表示と内部の時差だけ「分」を移動させた時間オブジェクト
     */
    private function adjustFromFormat(Peach_DT_Time $d)
    {
        return $d->add("minute", $this->internalOffset - $this->externalOffset);
    }
}
