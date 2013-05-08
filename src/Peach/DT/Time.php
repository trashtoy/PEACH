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
 * ある特定の日付または時刻を表すインタフェースです.
 * このインタフェースを実装したオブジェクトのことを「時間オブジェクト」と呼びます.
 * 時間オブジェクトには以下の 3 種類の型があります.
 * 
 * - DATE : 年・月・日のフィールドを持ちます
 * - DATETIME : 年・月・日・時・分のフィールドを持ちます
 * - TIMESTAMP : 年・月・日・時・分・秒のフィールドを持ちます
 * 
 * とある時間オブジェクトについて, そのオブジェクトの型が何かを調べるには
 * {@link Peach_DT_Time::getType() getType()} メソッドを使用してください.
 * 
 * 時間オブジェクトを操作するための各種メソッド (get, add, set, setAll など) 
 * は文字列型の引数を指定して呼び出す仕様となっていますが,
 * 具体的には "year", "month", "date", "hour", "minute", "second"
 * などの値を指定してください. (大文字・小文字を問いません)
 * 
 * 具体的には以下の仕様となっています.
 * 
 * - 先頭が "Y"  : 年
 * - 先頭が "MO" : 月
 * - 先頭が "D"  : 日
 * - 先頭が "H"  : 時
 * - 先頭が "M" で始まり, 直後に "O" が続かない場合 : 分
 * - 先頭が "S"  : 秒
 * 
 * このクラスはイミュータブルであるため, 各種フィールド操作メソッド (add, set, setAll) 
 * は新しい時間オブジェクトを返すことに注意してください.
 * 
 * このクラスは {@link Util_Comparable} を実装しているため, {@link Util_Arrays::sort()} でソートすることが出来ます.
 * 
 * @package DT
 */
interface Peach_DT_Time extends Peach_Util_Comparable
{
    /**
     * このオブジェクトが年・月・日のフィールドをサポートしていることを示します.
     */
    const TYPE_DATE      = 129;
    
    /**
     * このオブジェクトが年・月・日・時・分のフィールドをサポートしていることを示します.
     */
    const TYPE_DATETIME  = 130;
    
    /**
     * このオブジェクトが年・月・日・時・分・秒のフィールドをサポートしていることを示します.
     */
    const TYPE_TIMESTAMP = 131;
    
    /**
     * 日曜日をあらわす定数です.
     * @var int
     */
    const SUNDAY    = 0;
    
    /**
     * 月曜日をあらわす定数です
     * @var int
     */
    const MONDAY    = 1;
    
    /**
     * 火曜日をあらわす定数です
     * @var int
     */
    const TUESDAY   = 2;
    
    /**
     * 水曜日をあらわす定数です
     * @var int
     */
    const WEDNESDAY = 3;
    
    /**
     * 木曜日をあらわす定数です
     * @var int
     */
    const THURSDAY  = 4;
    
    /**
     * 金曜日をあらわす定数です
     * @var int
     */
    const FRIDAY    = 5;
    
    /**
     * 土曜日をあらわす定数です
     * @var int
     */
    const SATURDAY  = 6;
    
    /**
     * このオブジェクトの型を返します.
     * 返り値の数値は, 上位の (より多くのフィールドをサポートしている) 型ほど大きくなります.
     * そのため, 2 つの時間オブジェクトの getType の返り値を比較することにより, 
     * どちらのオブジェクトのほうがサポートしているフィールドが多いか調べることができます.
     * 
     * @return int オブジェクトの型
     * 
     * @see Peach_DT_Time::TYPE_DATE
     * @see Peach_DT_Time::TYPE_DATETIME
     * @see Peach_DT_Time::TYPE_TIMESTAMP
     */
    public function getType();
    
    /**
     * 指定されたフィールドの値を取得します.
     * @param  string $field フィールド名
     * @return int           対象フィールドの値. ただしフィールド名が不正な場合は NULL
     */
    public function get($field);
    
    /**
     * この時間オブジェクトの指定されたフィールドを上書きします.
     * 
     * @param  string $field フィールド名
     * @param  int    $value 設定する値
     * @return Peach_DT_Time 設定後の時間オブジェクト
     */
    public function set($field, $value);
    
    /**
     * この時間オブジェクトの複数のフィールドを一度に上書きします.
     * 引数には, 
     * <code>array("year" => 2010, "month" => 8, "date" => 31)</code>
     * などの配列か, または同様の Map オブジェクトを指定してください.
     * 
     * @param  Peach_Util_Map|array フィールドと値の一覧
     * @return Peach_DT_Time        設定後の時間オブジェクト
     */
    public function setAll($subject);
    
    /**
     * 引数のフィールドを, $amount だけ増加 (負の場合は減少) させます.
     * @param  string  対象のフィールド
     * @param  int     加算する量. マイナスの場合は過去方向に移動する.
     * @return Peach_DT_Time 設定後の時間オブジェクト
     */
    public function add($field, $amount);
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * フォーマットを指定しない場合はデフォルトの方法 (SQL などで使われる慣用表現) で書式化を行ないます.
     * @param  Peach_DT_Format $format
     * @return string            このオブジェクトの書式化.
     *                           引数を指定しない場合は "YYYY-MM-DD" あるいは "YYYY-MM-DD hh:mm:ss" などの文字列
     */
    public function format(Peach_DT_Format $format = null);
    
    /**
     * 指定されたオブジェクトとこのオブジェクトを比較します.
     * 二つのオブジェクトが等しいと判断された場合に TRUE を返します.
     *
     * @param  mixed   $obj 比較対象のオブジェクト
     * @return boolean      二つのオブジェクトが等しい場合に TRUE, それ以外は FALSE
     */
    public function equals($obj);
    
    /**
     * 指定された時間とこの時間を比較します.
     *
     * もしもこのオブジェクトが持つ時間フィールドすべてが
     * 引数のオブジェクトの時間フィールドと一致した場合, 
     * より多くの時間フィールドを持つほうが「後」となります.
     * 
     * 例: 2012-05-21 (Peach_DT_Date) < 2012-05-21T00:00 (Peach_DT_Datetime) < 2012-05-21T00:00:00 (Peach_DT_Timestamp)
     *
     * @param  Peach_DT_Time $time 比較対象の時間
     * @return boolean         この時間のほうが過去である場合は TRUE, それ以外は FALSE
     */
    public function before(Peach_DT_Time $time);
    
    /**
     * 指定された時間とこの時間を比較します.
     *
     * もしもこのオブジェクトが持つ時間フィールドすべてが
     * 引数のオブジェクトの時間フィールドと一致した場合, 
     * より多くの時間フィールドを持つほうが「後」となります.
     * 
     * 例: 2012-05-21 (Peach_DT_Date) < 2012-05-21T00:00 (Peach_DT_Datetime) < 2012-05-21T00:00:00 (Peach_DT_Timestamp)
     *
     * @param  Peach_DT_Time $time 比較対象の時間
     * @return boolean   この時間のほうが未来である場合は TRUE, それ以外は FALSE
     */
    public function after(Peach_DT_Time $time);
    
    /**
     * この年がうるう年かどうかを判定します.
     *
     * うるう年の判別ルールは以下の通りです.
     * - 4 で割り切れるはうるう年である
     * - ただし、100 で割り切れる年はうるう年ではない
     * - ただし、400 で割り切れる年はうるう年である
     * 
     * @return bool うるう年である場合に TRUE, それ以外は FALSE
     */
    public function isLeapYear();
    
    /**
     * この日付の曜日を返します. 返される値は 0 から 6 までの整数で, 0 が日曜, 6 が土曜をあらわします.
     * それぞれの整数は, このクラスで定義されている各定数に対応しています.
     * 
     * @return int 曜日 (0 以上 6 以下の整数)
     * 
     * @see    Peach_DT_Time::SUNDAY
     * @see    Peach_DT_Time::MONDAY
     * @see    Peach_DT_Time::TUESDAY
     * @see    Peach_DT_Time::WEDNESDAY
     * @see    Peach_DT_Time::THURSDAY
     * @see    Peach_DT_Time::FRIDAY
     * @see    Peach_DT_Time::SATURDAY
     */
    public function getDay();
    
    /**
     * この月の日数を返します.
     * @return int この月の日数. すなわち, 28 から 31 までの整数.
     */
    public function getDateCount();
    
    /**
     * このオブジェクトを DATE 型にキャストします.
     * このメソッドの返り値は以下の性質を持ちます.
     * 
     * - 年・月・日のフィールドをサポートします.
     * - {@link Peach_DT_Time::getType() getType()} を実行した場合 {@link Peach_DT_Time::TYPE_DATE} を返します.
     * 
     * デフォルトの実装では {@link Peach_DT_Date} 型にキャストします.
     *
     * @return Peach_DT_Time DATE 型の時間オブジェクト
     */
    public function toDate();
    
    /**
     * このオブジェクトを DATETIME 型にキャストします.
     * このメソッドの返り値は以下の性質を持ちます.
     * 
     * - 年・月・日・時・分のフィールドをサポートします.
     * - {@link Peach_DT_Time::getType() getType()} を実行した場合 {@link Peach_DT_Time::TYPE_DATETIME} を返します.
     * 
     * デフォルトの実装では {@link Peach_DT_Datetime} オブジェクトを返します.
     * 
     * @return Peach_DT_Time DATETIME 型の時間オブジェクト
     */
    public function toDatetime();
    
    /**
     * このオブジェクトを TIMESTAMP 型にキャストします.
     * このメソッドの返り値は以下の性質を持ちます.
     * 
     * - 年・月・日・時・分・秒のフィールドをサポートします.
     * - {@link Peach_DT_Time::getType() getType()} を実行した場合 {@link Peach_DT_Time::TYPE_TIMESTAMP} を返します.
     * 
     * デフォルトの実装では {@link Peach_DT_Timestamp} オブジェクトを返します.
     * 
     * @return Peach_DT_Time TIMESTAMP 型の時間オブジェクト
     */
    public function toTimestamp();
    
    /**
     * この時間の時刻 (時・分・秒) 部分を書式化します.
     * 返される文字列はタイプによって以下の通りとなります.
     * 
     * - DATE : 空文字列
     * - DATETIME : "hh:mm" 
     * - TIMESTAMP : "hh:mm:ss"
     * 
     * @return string 時刻部分の文字列. このオブジェクトが時刻をサポートしない場合は空文字列.
     */
    public function formatTime();
}
?>