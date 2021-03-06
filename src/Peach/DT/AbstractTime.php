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
 * 時間を表す抽象基底クラスです.
 * {@link Peach_DT_Date}, {@link Peach_DT_Datetime}, {@link Peach_DT_Timestamp}
 * の共通部分の実装です.
 * 
 * @package DT
 */
abstract class Peach_DT_AbstractTime implements Peach_DT_Time
{
    /**
     * 年フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $YEAR   = 0;
    
    /**
     * 月フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $MONTH  = 1;
    
    /**
     * 日フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $DATE   = 2;
    
    /**
     * 時フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $HOUR   = 3;
    
    /**
     * 分フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $MINUTE = 4;
    
    /**
     * 秒フィールドのキーです
     * @var int
     * @ignore
     */
    protected static $SECOND = 5;
    
    /**
     * 年・月・日などの各種フィールドです.
     * 
     * @var Peach_Util_Map
     * @ignore
     */
    protected $fields;
    
    /**
     * 指定されたフィールドの値を取得します.
     * @param  string $field フィールド名
     * @return int           対象フィールドの値. ただしフィールド名が不正な場合は NULL
     */
    public final function get($field)
    {
        $index = $this->getFieldIndex($field);
        return $this->fields->get($index);
    }
    
    /**
     * この時間オブジェクトの指定されたフィールドを上書きします.
     * 
     * @param  string $field フィールド名
     * @param  int    $value 設定する値
     * @return Peach_DT_Time 設定後の時間オブジェクト
     */
    public final function set($field, $value)
    {
        $index = $this->getFieldIndex($field);
        $newFields = new Peach_Util_ArrayMap($this->fields);
        $newFields->put($index, $value);
        return $this->newInstance($newFields);
    }
    
    /**
     * この時間オブジェクトの複数のフィールドを一度に上書きします.
     * 引数には, 
     * <code>array("year" => 2010, "month" => 8, "date" => 31)</code>
     * などの配列か, または同様の Map オブジェクトを指定してください.
     * 
     * @param  Peach_Util_Map|array $subject フィールドと値の一覧
     * @return Peach_DT_Time                 設定後の時間オブジェクト
     */
    public final function setAll($subject)
    {
        if (is_array($subject)) {
            $subject = new Peach_Util_ArrayMap($subject);
        }
        if (!($subject instanceof Peach_Util_Map)) {
            throw new InvalidArgumentException("Argument (" . Peach_Util_Values::getType($subject) . ") must be array or Peach_Util_Map");
        }
        $newFields = new Peach_Util_ArrayMap($this->fields);
        $entryList = $subject->entryList();
        foreach ($entryList as $entry) {
            $index = $this->getFieldIndex($entry->getKey());
            $newFields->put($index, $entry->getValue());
        }
        return $this->newInstance($newFields);
    }
    
    /**
     * 引数のフィールドを, $amount だけ増加 (負の場合は減少) させます.
     * @param  string $field  対象のフィールド
     * @param  int    $amount 加算する量. マイナスの場合は過去方向に移動する.
     * @return Peach_DT_Time  設定後の時間オブジェクト
     */
    public final function add($field, $amount)
    {
        $newFields = new Peach_Util_ArrayMap($this->fields);
        $key       = $this->getFieldIndex($field);
        $current   = $this->fields->get($key);
        $newFields->put($key, $current + $amount);
        return $this->newInstance($newFields);
    }
    
    /**
     * この時間と指定された時間を比較します.
     * このメソッドは, 自身と引数の時間オブジェクトが共通で持っているフィールドについて比較を行います.
     * 比較の結果すべてのフィールドの値が等しかった場合, 
     * より多くの時間フィールドを持つほうが「後」となります.
     * 
     * 例: 2012-05-21 (Peach_DT_Date) < 2012-05-21T00:00 (Peach_DT_Datetime) < 2012-05-21T00:00:00 (Peach_DT_Timestamp)
     * 
     * @param  mixed $obj 比較対象のオブジェクト
     * @return int        この時間のほうが過去の場合は負の値, 未来の場合は正の値, 等しい場合は 0.
     *                    ただし, 引数が時間オブジェクトでない場合は NULL
     */
    public final function compareTo($obj)
    {
        if ($obj instanceof Peach_DT_Time) {
            $c = $this->compareFields($obj);
            return ($c !== 0) ? $c : $this->getType() - $obj->getType();
        } else {
            return null;
        }
    }
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * フォーマットを指定しない場合はデフォルトの方法 (SQL などで使われる慣用表現) で書式化を行ないます.
     * @param  Peach_DT_Format $format
     * @return string
     */
    public final function format(Peach_DT_Format $format = null)
    {
        return isset($format) ? $this->handleFormat($format) : $this->__toString();
    }
    
    /**
     * 指定されたオブジェクトとこのオブジェクトを比較します.
     * compareTo による比較結果が 0 を返し, かつクラスが同じ場合に TRUE を返します.
     *
     * @param  mixed   $obj 比較対象のオブジェクト
     * @return bool         二つのオブジェクトが等しい場合に TRUE, それ以外は FALSE
     */
    public function equals($obj)
    {
        if (get_class($this) != get_class($obj)) {
            return false;
        }
        return $this->compareTo($obj) === 0;
    }
    
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
     * @return bool          この時間のほうが過去である場合は TRUE, それ以外は FALSE
     */
    public final function before(Peach_DT_Time $time)
    {
        $c = $this->compareTo($time);
        return isset($c) && ($c < 0);
    }
    
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
     * @return bool          この時間のほうが未来である場合は TRUE, それ以外は FALSE
     */
    public final function after(Peach_DT_Time $time)
    {
        $c = $this->compareTo($time);
        return isset($c) && (0 < $c);
    }
    
    /**
     * この時間の時刻 (時・分・秒) 部分を書式化します.
     * 時・分・秒をサポートしていないオブジェクトの場合は空文字列を返します.
     * 
     * @return string "hh:mm:ss" 形式の文字列. このオブジェクトが時刻をサポートしない場合は空文字列.
     */
    public function formatTime()
    {
        return "";
    }
    
    /**
     * このオブジェクトが指す時刻を, SQL などで使われる慣用表現に変換して返します.
     *
     * @return string このオブジェクトの文字列表現 ("YYYY-MM-DD", "YYYY-MM-DD hh:mm" など)
     * @codeCoverageIgnore
     */
    public function __toString()
    {
        return (string) $this;
    }
    
    /**
     * 指定されたフィールドを使ってこの時間オブジェクトを初期化します.
     * サブクラスはこのメソッドを継承して独自の初期化処理を行ないます.
     * 
     * @param  Peach_Util_Map $fields
     * @ignore
     */
    protected function init(Peach_Util_Map $fields)
    {
        $this->adjust($fields);
        $this->fields = $fields;
    }
    
    /**
     * 時間の不整合を調整します.
     * 例えば, 0 から 23 までの値を取るはずの「時」のフィールドが
     * 24 以上の値を持っていた場合に, 日付の繰上げ処理を行うなどの操作を行います.
     * 
     * このメソッドはサブクラスのコンストラクタ内で参照されます.
     * 
     * @param Peach_Util_Map $fields フィールド一覧
     * @ignore
     */
    protected abstract function adjust(Peach_Util_Map $fields);
    
    /**
     * 指定されたフィールドを持つ新しいインスタンスを構築します.
     * 
     * @param  Peach_Util_Map $fields 各種フィールド
     * @return Peach_DT_Time          新しいインスタンス
     * @ignore
     */
    protected abstract function newInstance(Peach_Util_Map $fields);
    
    /**
     * このオブジェクトと指定された時間オブジェクトについて,
     * 共通するフィールド同士を比較します.
     * このメソッドは compareTo() から参照されます.
     * 
     * @param  Peach_DT_Time $time
     * @return int
     * @see    Peach_DT_Time::compareTo()
     * @ignore
     */
    protected abstract function compareFields(Peach_DT_Time $time);
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * このメソッドは format() から参照されます.
     * 
     * @param  Peach_DT_Format $format
     * @return string
     * @see    Peach_DT_Time::format()
     * @ignore
     */
    protected abstract function handleFormat(Peach_DT_Format $format);
    
    /**
     * 指定された日付の曜日を返します. 返される値は 0 から 6 までの整数で, 0 が日曜, 6 が土曜をあらわします.
     * プログラム内で各曜日を表現する場合は, ソースコード内に数値を直接書き込む代わりに
     * Peach_DT_Time::SUNDAY や Peach_DT_Time::SATURDAY などの定数を使ってください.
     * 
     * @param  int $y 年
     * @param  int $m 月
     * @param  int $d 日
     * @return int 曜日 (0 以上 6 以下の整数)
     * 
     * @see    Peach_DT_Date::SUNDAY
     * @see    Peach_DT_Date::MONDAY
     * @see    Peach_DT_Date::TUESDAY
     * @see    Peach_DT_Date::WEDNESDAY
     * @see    Peach_DT_Date::THURSDAY
     * @see    Peach_DT_Date::FRIDAY
     * @see    Peach_DT_Date::SATURDAY
     * @ignore
     */
    protected static function getDayOf($y, $m, $d)
    {
        static $m_sub = array(0, 0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4);
        if ($m < 3) {
            $y --;
        }
        return ($y + intval($y / 4) - intval($y / 100) + intval($y / 400) + $m_sub[$m] + $d) % 7;
    }
    
    /**
     * 指定されたフィールド名を $fields のインデックスに変換します.
     * 不正なフィールド名の場合は -1 を返します.
     * 
     * @param  string $field フィールド名
     * @return int           インデックス
     * 
     * @see    Peach_DT_Time::$YEAR
     * @see    Peach_DT_Time::$MONTH
     * @see    Peach_DT_Time::$DATE
     * @see    Peach_DT_Time::$HOUR
     * @see    Peach_DT_Time::$MINUTE
     * @see    Peach_DT_Time::$SECOND
     * @codeCoverageIgnore
     */
    private function getFieldIndex($field)
    {
        static $mapping = null;
        if ($mapping === null) {
            $mapping = array(
                "y"  => self::$YEAR,
                "mo" => self::$MONTH,
                "d"  => self::$DATE,
                "h"  => self::$HOUR,
                "m"  => self::$MINUTE,
                "s"  => self::$SECOND
            );
        }
        
        $f = strtolower($field);
        foreach ($mapping as $key => $index) {
            if (Peach_Util_Strings::startsWith($f, $key)) {
                return $index;
            }
        }
        return -1;
    }
}
