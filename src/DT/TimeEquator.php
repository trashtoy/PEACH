<?php
/** @package DT */
/** */
require_once(dirname(__FILE__) . "/../Util/load.php");

/**
 * 時間オブジェクトの比較を行うための Equator です.
 * このクラスは, 時間オブジェクトをキーとする {@link Util_HashMap HashMap}
 * を構築する際に使用してください.
 * 
 * @package DT
 */
class DT_TimeEquator implements Util_Equator {
    /**
     * @var array
     */
    private $fields;
    
    /**
     * 比較対象のフィールドを指定して, 新しい DT_TimeEquator オブジェクトを作成します.
     * 引数の指定方法には以下の方法があります.
     * 
     * -未指定, または空の配列 ({@link DT_Time::equals()} を使って比較を行います)
     * -比較対象のフィールドの配列 (例: array("hour", "minute", "second") など)
     * -{@link DT_Time::TYPE_DATE} … array("year", "month", "date") と等価です
     * -{@link DT_Time::TYPE_DATETIME} … array("year", "month", "date", "hour", "minute") と等価です
     * -{@link DT_Time::TYPE_TIMESTAMP} … array("year", "month", "date", "hour", "minute", "second") と等価です
     * 
     * @param mixed $fields 比較対象のフィールド一覧
     */
    public function __construct($fields = NULL) {
        $this->fields = $this->initFields($fields);
    }
    
    /**
     * このオブジェクトの比較対象フィールド一覧を初期化します.
     * 
     * @param  mixed $fields
     * @return array
     */
    private function initFields($fields) {
        switch ($fields) {
            case DT_Time::TYPE_DATE:
                return $this->initFields(array("year", "month", "date"));
            case DT_Time::TYPE_DATETIME:
                return $this->initFields(array("year", "month", "date", "hour", "minute"));
            case DT_Time::TYPE_TIMESTAMP:
                return $this->initFields(array("year", "month", "date", "hour", "minute", "second"));
        }
        
        if (is_array($fields)) {
            return count($fields) ? $fields : NULL;
        }
        else if (isset($fields)) {
            return array($fields);
        }
        else {
            return NULL;
        }
    }
    
    /**
     * デフォルトの DT_Equator オブジェクトを返します.
     * このオブジェクトは {@link DT_Time::equals()} を使って等値性を調べます.
     * 
     */
    public static function getDefault() {
        static $instance = NULL;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * 指定された 2 つの時間オブジェクトが等しいかどうか調べます.
     * この Equator に設定されているフィールドについて比較を行い,
     * 全て等しい場合のみ TRUE を返します.
     * 
     * 
     * @param  DT_Time $var1 比較対象の時間オブジェクト
     * @param  DT_Time $var2 比較対象の時間オブジェクト
     * @return bool          2 つの時間オブジェクトが等しいと判断された場合のみ TRUE
     * @throws Exception 引数に DT_Time インスタンス以外の値が指定された場合
     */
    public function equate($var1, $var2) {
        if (!($var1 instanceof DT_Time) || !($var2 instanceof DT_Time)) {
            $arg1 = Util_Values::getType($var1);
            $arg2 = Util_Values::getType($var2);
            throw new Exception("arguments must be DT_Time instance.({$arg1}, {$arg2})");
        }
        
        $fields = $this->fields;
        if (isset($fields)) {
            foreach ($fields as $field) {
                if ($var1->get($field) !== $var2->get($field)) {
                    return FALSE;
                }
            }
            return TRUE;
        }
        else {
            return $var1->equals($var2);
        }
    }
    
    /**
     * 年・月・日・時・分・秒の各フィールドからハッシュ値を算出します.
     * 
     * @param  mixed $var
     * @return int
     * @throws Exception 引数が DT_Time インスタンスでなかった場合
     */
    public function hashCode($var) {
        if (!($var instanceof DT_Time)) {
            $type = Util_Values::getType($var);
            throw new Exception("The value must be DT_Time instance.({$type})");
        }
        
        return 
            $var->get("year")              +
            $var->get("month")  *       31 +  // 31^1
            $var->get("date")   *      961 +  // 31^2
            $var->get("hour")   *    29791 +  // 31^3
            $var->get("minute") *   923521 +  // 31^4
            $var->get("second") * 28629151;   // 31^5
    }
}
?>