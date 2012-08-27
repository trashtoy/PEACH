<?php
/** @package DT */
/**
 * 既存の時間オブジェクトを機能拡張するためのラッパークラスです.
 * このクラスは Decorator パターンで設計されています.
 * 
 * @package DT
 */
class DT_TimeWrapper implements DT_Time {
    /**
     * ラップする時間オブジェクトです.
     * @var DT_Time
     */
    private $original;
    
    /**
     * 指定された時間オブジェクトをラップする TimeWrapper を構築します.
     * 
     * @param DT_Time $original ラップ対象の時間オブジェクト
     */
    public function __construct(DT_Time $original) {
        $this->original = $original;
    }
    
    /**
     * ラップ対象の時間オブジェクトを返します.
     * @return DT_Time
     */
    public function getOriginal() {
        return $this->original;
    }
    
    /**
     * ラップ対象のオブジェクトのタイプを返します.
     * @return int
     */
    public function getType() {
        return $this->original->getType();
    }
    
    /**
     * ラップ対象のオブジェクトの before メソッドを実行します.
     * @param  DT_Time $time
     * @return bool
     */
    public function before(DT_Time $time) {
        return $this->original->before($time);
    }
    
    /**
     * ラップ対象のオブジェクトの after メソッドを実行します.
     * @param  DT_Time $time
     * @return bool
     */
    public function after(DT_Time $time) {
        return $this->original->after($time);
    }
    
    /**
     * ラップ対象のオブジェクトの compareTo メソッドを実行します.
     * @param  DT_Time $subject
     * @return bool
     */
    public function compareTo($subject) {
        return $this->original->compareTo($subject);
    }
    
    /**
     * 指定されたインスタンスをラップする新しい TimeWrapper を構築します.
     * 
     * @param  DT_Time $instance ラップ対象のオブジェクト
     * @return DT_TimeWrapper
     */
    protected function newInstance(DT_Time $instance) {
        return new self($instance);
    }
    
    /**
     * ラップ対象のオブジェクトの add メソッドを実行し, その返り値をこのクラスでラップします.
     * 
     * @param  string $field
     * @param  int    $amount
     * @return DT_TimeWrapper
     */
    public function add($field, $amount) {
        return $this->newInstance($this->original->add($field, $amount));
    }
    
    /**
     * ラップ対象のオブジェクトの set メソッドを実行し, その返り値をこのクラスでラップします.
     * 
     * @param  string $field
     * @param  int $value
     * @return DT_TimeWrapper
     */
    public function set($field, $value) {
        return $this->newInstance($this->original->set($field, $value));
    }
    
    /**
     * ラップ対象のオブジェクトの setAll メソッドを実行し, その返り値をこのクラスでラップします.
     * @param  array|ArrayMap $subject
     * @return DT_TimeWrapper
     */
    public function setAll($subject) {
        return $this->newInstance($this->original->setAll($subject));
    }
    
    /**
     * ラップ対象のオブジェクトの get メソッドを実行します.
     * 
     * @param  string $field
     * @return int
     */
    public function get($field) {
        return $this->original->get($field);
    }
    
    /**
     * ラップ対象のオブジェクトの format メソッドを実行します.
     * @param  DT_Format $format
     * @return string
     */
    public function format(DT_Format $format = NULL) {
        return $this->original->format($format);
    }
    
    /**
     * ラップ対象のオブジェクトの formatTime メソッドを実行します.
     * @return string
     */
    public function formatTime() {
        return $this->original->formatTime();
    }
    
    /**
     * ラップ対象のオブジェクトの equals メソッドを実行します.
     * @param  mixed $obj
     * @return bool
     */
    public function equals($obj) {
        return $this->original->equals($obj);
    }
    
    /**
     * ラップ対象のオブジェクトの toDate メソッドを実行します.
     * @return DT_Date
     */
    public function toDate() {
        return $this->original->toDate();
    }
    
    /**
     * ラップ対象のオブジェクトの toDatetime メソッドを実行します.
     * @return DT_Datetime
     */
    public function toDatetime() {
        return $this->original->toDatetime();
    }
    
    /**
     * ラップ対象のオブジェクトの toTimestamp メソッドを実行します.
     * @return DT_Timestamp
     */
    public function toTimestamp() {
        return $this->original->toTimestamp();
    }
    
    /**
     * ラップ対象のオブジェクトの __toString メソッドを実行します.
     * @param  DT_Time $time
     * @return string
     */
    public function __toString() {
        return $this->original->__toString();
    }
}
?>