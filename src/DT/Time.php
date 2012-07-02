<?php
/** @package DT */
/** */
require_once('Util/Arrays.php');
require_once('Util/ArrayMap.php');
require_once('Util/Strings.php');
require_once('Util/Comparable.php');
/**
 * 時間を表す抽象基底クラスです.
 * 
 * このクラスは {@link Util_Comparable} を実装しているため, {@link Util_Arrays::sort()} でソートすることが出来ます. 
 * 
 * @package DT
 */
abstract class DT_Time implements Util_Comparable {
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
     * @var Util_Map
     * @ignore
     */
    protected $fields;
    
    /**
     * 指定されたフィールドの値を取得します.
     * @param  string $field フィールド名
     * @return int           対象フィールドの値. ただしフィールド名が不正な場合は NULL
     */
    public final function get($field) {
        $index = $this->getFieldIndex($field);
        return $this->fields->get($index);
    }
    
    /**
     * この時間オブジェクトの指定されたフィールドを上書きします.
     * 
     * @param  string $field フィールド名
     * @param  int    $value 設定する値
     * @return DT_Time 設定後の時間オブジェクト
     */
    public final function set($field, $value) {
        $index = $this->getFieldIndex($field);
        $newFields = new Util_ArrayMap($this->fields);
        $newFields->put($index, $value);
        return $this->newInstance($newFields);
    }
    
    /**
     * この時間オブジェクトの複数のフィールドを一度に上書きします.
     * 引数には, 
     * <code>array("year" => 2010, "month" => 8, "date" => 31)</code>
     * などの配列か, または同様の Map オブジェクトを指定してください.
     * 
     * @param  Util_Map|array フィールドと値の一覧
     * @return DT_Time        設定後の時間オブジェクト
     */
    public final function setAll($subject) {
        if (is_array($subject)) {
            $subject = new Util_ArrayMap($subject);
        }
        if (!($subject instanceof Util_Map)) {
            throw new Exception();
        }
        $newFields = new Util_ArrayMap($this->fields);
        $entryList = $subject->entryList();
        foreach ($entryList as $entry) {
            $index = $this->getFieldIndex($entry->getKey());
            $newFields->put($index, $entry->getValue());
        }
        return $this->newInstance($newFields);
    }
    
    /**
     * 引数のフィールドを, $amount だけ増加 (負の場合は減少) させます.
     * @param  string  対象のフィールド
     * @param  int     加算する量. マイナスの場合は過去方向に移動する.
     * @return DT_Time 設定後の時間オブジェクト
     */
    public final function add($field, $amount) {
        $newFields = new Util_ArrayMap($this->fields);
        $key       = $this->getFieldIndex($field);
        $current   = $this->fields->get($key);
        $newFields->put($key, $current + $amount);
        return $this->newInstance($newFields);        
    }
    
    /**
     * この時間と指定された時間を比較します.
     * このメソッドは, 自身と引数の時間オブジェクトが共通で持っているフィールドについて比較を行います.
     * 比較の結果すべてのフィールドの値が等しかった場合, 
     * 
     * @param  mixed  比較対象のオブジェクト
     * @return int    この時間のほうが過去の場合は負の値, 未来の場合は正の値, 等しい場合は 0.
     *                 ただし, 引数が時間オブジェクトでない場合は NULL
     */
    public final function compareTo($obj) {
        if (!($obj instanceof DT_Time)) {
            return NULL;
        }
        
        $c = $this->compareFields($obj);
        if ($c !== 0) {
            return $c;
        }
        $className = get_class($this);
        if (get_class($obj) === $className) {
            return 0;
        }
        else {
            return ($obj instanceof $className) ? -1 : 1;
        }
    }
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * フォーマットを指定しない場合はデフォルトの方法 (SQL などで使われる慣用表現) で書式化を行ないます.
     * @param  DT_Format $format
     * @return string
     */
    public final function format(DT_Format $format = NULL) {
        return isset($format) ? $this->handleFormat($format) : $this->__toString();
    }
    
    /**
     * 指定されたオブジェクトとこのオブジェクトを比較します.
     * 二つのオブジェクトが等しいと判断された場合に TRUE を返します.
     *
     * @param  mixed   $obj 比較対象のオブジェクト
     * @return boolean      二つのオブジェクトが等しい場合に TRUE, それ以外は FALSE
     */
    public function equals($obj) {
        if (get_class($this) != get_class($obj)) return FALSE;
        return $this->compareTo($obj) === 0;
    }
    
    /**
     * 指定された時間とこの時間を比較します.
     *
     * もしもこのオブジェクトが持つ時間フィールドすべてが
     * 引数のオブジェクトの時間フィールドと一致した場合, 
     * より多くの時間フィールドを持つほうが「後」となります.
     * 例: 2012-05-21 (DT_Date) < 2012-05-21T00:00 (DT_Datetime) < 2012-05-21T00:00:00 (DT_Timestamp)
     *
     * @param  DT_Time $time 比較対象の時間
     * @return boolean         この時間のほうが過去である場合は TRUE, それ以外は FALSE
     */
    public function before(DT_Time $time) {
        $c = $this->compareTo($time);
        return isset($c) && ($c < 0);
    }
    
    /**
     * 指定された時間とこの時間を比較します.
     *
     * もしもこのオブジェクトが持つ時間フィールドすべてが
     * 引数のオブジェクトの時間フィールドと一致した場合, 
     * より多くの時間フィールドを持つほうが「後」となります.
     * 例: 2012-05-21 (DT_Date) < 2012-05-21T00:00 (DT_Datetime) < 2012-05-21T00:00:00 (DT_Timestamp)
     *
     * @param  DT_Time $time 比較対象の時間
     * @return boolean   この時間のほうが未来である場合は TRUE, それ以外は FALSE
     */
    public function after(DT_Time $time) {
        $c = $this->compareTo($time);
        return isset($c) && (0 < $c);
    }
    
    /**
     * この時間の時刻 (時・分・秒) 部分を書式化します.
     * 時・分・秒をサポートしていないオブジェクトの場合は空文字列を返します.
     * 
     * @return string "hh:mm:ss" 形式の文字列. このオブジェクトが時刻をサポートしない場合は空文字列.
     */
    public function formatTime() {
        return "";
    }
    
    /**
     * このオブジェクトの文字列表現を返します.
     *
     * @return string W3CDTF に則った文字列表現
     */
    public function __toString() {
        return (string) $this;
    }
    
    /**
     * このオブジェクトを {@link DT_Date} 型にキャストします.
     *
     * @return DT_Date このオブジェクトの date 表現
     */
    public abstract function toDate();
    
    /**
     * このオブジェクトを {@link DT_Datetime} 型にキャストします.
     *
     * @return DT_Datetime このオブジェクトの datetime 表現
     */
    public abstract function toDatetime();
    
    /**
     * このオブジェクトを {@link DT_Timestamp} 型にキャストします.
     *
     * @return DT_Timestamp このオブジェクトの timestamp 表現
     */
    public abstract function toTimestamp();
    
    /**
     * 指定されたフィールドを使ってこの時間オブジェクトを初期化します.
     * サブクラスはこのメソッドを継承して独自の初期化処理を行ないます.
     * 
     * @param  Util_Map $fields
     * @ignore
     */
    protected function init(Util_Map $fields) {
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
     * @param Util_Map フィールド一覧
     * @ignore
     */
    protected abstract function adjust(Util_Map $fields);
    
    /**
     * 指定されたフィールドを持つ新しいインスタンスを構築します.
     * 
     * @param  Util_Map 各種フィールド
     * @return DT_Time
     * @ignore
     */
    protected abstract function newInstance(Util_Map $fields);
    
    /**
     * このオブジェクトと指定された時間オブジェクトについて,
     * 共通するフィールド同士を比較します.
     * このメソッドは compareTo() から参照されます.
     * 
     * @param  DT_Time $time
     * @return int
     * @see    DT_Time::compareTo()
     * @ignore
     */
    protected abstract function compareFields(DT_Time $time);
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * このメソッドは format() から参照されます.
     * 
     * @param  DT_Format $format
     * @return string
     * @see    DT_Time::format()
     * @ignore
     */
    protected abstract function handleFormat(DT_Format $format);
    
    /**
     * 指定された日付の曜日を返します. 返される値は 0 から 6 までの整数で, 0 が日曜, 6 が土曜をあらわします.
     * プログラム内で各曜日を表現する場合は, ソースコード内に数値を直接書き込む代わりに
     * DT_Time::SUNDAY や DT_Time::SATURDAY などの定数を使ってください.
     * 
     * @param  int $y 年
     * @param  int $m 月
     * @param  int $d 日
     * @return int 曜日 (0 以上 6 以下の整数)
     * 
     * @see    DT_Date::SUNDAY
     * @see    DT_Date::MONDAY
     * @see    DT_Date::TUESDAY
     * @see    DT_Date::WEDNESDAY
     * @see    DT_Date::THURSDAY
     * @see    DT_Date::FRIDAY
     * @see    DT_Date::SATURDAY
     * @ignore
     */
    protected static function getDayOf($y, $m, $d) {
        static $m_sub = array(0, 0, 3, 2, 5, 0, 3, 5, 1, 4, 6, 2, 4);
        if ($m < 3) $y --;
        return ($y + intval($y / 4) - intval($y / 100) + intval($y / 400) + $m_sub[$m] + $d) % 7;        
    }
    
    /**
     * 2つの時間オブジェクトを比較します. 一つめの引数のほうが後 (未来) の場合は正の値,
     * 前 (過去) の場合は負の値, 同じ場合は 0 を返します.
     * 
     * このメソッドは, 一つ目の時間オブジェクトの {@link DT_Time::compareTo()} メソッドを呼び出し
     * 二つ目の時間オブジェクトと比較した結果を返します.
     *
     * @param  DT_Time $time1 1つ目の時間
     * @param  DT_Time $time2 2つ目の時間
     * @return int            1つ目の時間を2つ目の時間と比較した結果
     * @see    DT_Time::compareTo()
     */
    public static function compareTime(DT_Time $time1, DT_Time $time2) {
        return $time1->compareTo($time2);
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最も古いものを返します.
     * 引数に DT_Time 型オブジェクトを羅列するか, DT_Time 型オブジェクトの配列を指定してください.
     * 引数に DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param array|DT_Time $time...
     * @return DT_Time      引数の中で最も古い DT_Time オブジェクト. 
     *                       引数が空か, DT_Time オブジェクトが含まれていない場合は NULL
     */
    public static function oldest() {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Util_Arrays::min(Util_Arrays::pickup($args, "DT_Time"));
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最新のものを返します. 
     * 引数に DT_Time 型オブジェクトを羅列するか, DT_Time 型オブジェクトの配列を指定してください.
     * 引数に DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param  array|DT_Time $time...
     * @return DT_Time       引数の中で最新の DT_Time オブジェクト. 
     *                        引数が空か, DT_Time オブジェクトが含まれていないの場合は NULL
     */
    public static function latest() {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Util_Arrays::max(Util_Arrays::pickup($args, "DT_Time"));
    }
    
    /**
     * 指定されたフィールド名を $fields のインデックスに変換します.
     * 不正なフィールド名の場合は -1 を返します.
     * 
     * @param  string フィールド名
     * @return int    インデックス
     * 
     * @see    DT_Time::YEAR
     * @see    DT_Time::MONTH
     * @see    DT_Time::DATE
     * @see    DT_Time::DATETIME
     * @see    DT_Time::TIMESTAMP
     */
    private function getFieldIndex($field) {
        static $mapping;
        if (!isset($mapping)) {
            $mapping = array(
                "y"  => self::$YEAR, 
                "mo" => self::$MONTH, 
                "d"  => self::$DATE, 
                "h"  => self::$HOUR,
                "m"  => self::$MINUTE, 
                "s"  => self::$SECOND
            );
        }
        
        $field = strtolower($field);
        foreach ($mapping as $key => $index) {
            if (Util_Strings::startsWith($field, $key)) {
                return $index;
            }
        }
        return -1;
    }
}
?>