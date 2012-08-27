<?php
/** @package DT */
/** */
require_once('Util/load.php');

/**
 * ある特定の日付または時刻を表すインタフェースです.
 * 
 * このクラスは {@link Util_Comparable} を実装しているため, {@link Util_Arrays::sort()} でソートすることが出来ます. 
 * 
 * @package DT
 */
interface DT_Time extends Util_Comparable {
    /**
     * このオブジェクトが年・月・日のフィールドをサポートしていることを示します.
     */
    const TYPE_DATE      = 1;
    
    /**
     * このオブジェクトが年・月・日・時・分のフィールドをサポートしていることを示します.
     */
    const TYPE_DATETIME  = 2;
    
    /**
     * このオブジェクトが年・月・日・時・分・秒のフィールドをサポートしていることを示します.
     */
    const TYPE_TIMESTAMP = 3;
    
    /**
     * このオブジェクトの型を返します.
     * 返り値の数値は, 上位の (より多くのフィールドをサポートしている) 型ほど大きくなります.
     * そのため, 2 つの時間オブジェクトの getType の返り値を比較することにより, 
     * どちらのオブジェクトのほうがサポートしているフィールドが多いか調べることができます.
     * 
     * @return int オブジェクトの型
     * 
     * @see DT_Time::TYPE_DATE
     * @see DT_Time::TYPE_DATETIME
     * @see DT_Time::TYPE_TIMESTAMP
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
     * @return DT_Time 設定後の時間オブジェクト
     */
    public function set($field, $value);
    
    /**
     * この時間オブジェクトの複数のフィールドを一度に上書きします.
     * 引数には, 
     * <code>array("year" => 2010, "month" => 8, "date" => 31)</code>
     * などの配列か, または同様の Map オブジェクトを指定してください.
     * 
     * @param  Util_Map|array フィールドと値の一覧
     * @return DT_Time        設定後の時間オブジェクト
     */
    public function setAll($subject);
    
    /**
     * 引数のフィールドを, $amount だけ増加 (負の場合は減少) させます.
     * @param  string  対象のフィールド
     * @param  int     加算する量. マイナスの場合は過去方向に移動する.
     * @return DT_Time 設定後の時間オブジェクト
     */
    public function add($field, $amount);
    
    /**
     * 指定されたフォーマットを使ってこの時間オブジェクトを書式化します.
     * フォーマットを指定しない場合はデフォルトの方法 (SQL などで使われる慣用表現) で書式化を行ないます.
     * @param  DT_Format $format
     * @return string
     */
    public function format(DT_Format $format = NULL);
    
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
     * 例: 2012-05-21 (DT_Date) < 2012-05-21T00:00 (DT_Datetime) < 2012-05-21T00:00:00 (DT_Timestamp)
     *
     * @param  DT_Time $time 比較対象の時間
     * @return boolean         この時間のほうが過去である場合は TRUE, それ以外は FALSE
     */
    public function before(DT_Time $time);
    
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
    public function after(DT_Time $time);
    
    
    /**
     * このオブジェクトを {@link DT_Date} 型にキャストします.
     *
     * @return DT_Date このオブジェクトの date 表現
     */
    public function toDate();
    
    /**
     * このオブジェクトを {@link DT_Datetime} 型にキャストします.
     *
     * @return DT_Datetime このオブジェクトの datetime 表現
     */
    public function toDatetime();
    
    /**
     * このオブジェクトを {@link DT_Timestamp} 型にキャストします.
     *
     * @return DT_Timestamp このオブジェクトの timestamp 表現
     */
    public function toTimestamp();
    
    /**
     * この時間の時刻 (時・分・秒) 部分を書式化します.
     * 時・分・秒をサポートしていないオブジェクトの場合は空文字列を返します.
     * 
     * @return string "hh:mm:ss" 形式の文字列. このオブジェクトが時刻をサポートしない場合は空文字列.
     */
    public function formatTime();
    
    
}
?>