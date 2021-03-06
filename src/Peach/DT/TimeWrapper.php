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
 * 既存の時間オブジェクトを機能拡張するためのラッパークラスです.
 * このクラスは Decorator パターンで設計されています.
 * 
 * @package DT
 */
class Peach_DT_TimeWrapper implements Peach_DT_Time
{
    /**
     * ラップする時間オブジェクトです.
     * @var Peach_DT_Time
     */
    private $original;
    
    /**
     * 指定された時間オブジェクトをラップする TimeWrapper を構築します.
     * 
     * @param Peach_DT_Time $original ラップ対象の時間オブジェクト
     */
    public function __construct(Peach_DT_Time $original)
    {
        $this->original = $original;
    }
    
    /**
     * ラップ対象の時間オブジェクトを返します.
     * @return Peach_DT_Time
     */
    public function getOriginal()
    {
        return $this->original;
    }
    
    /**
     * ラップ対象のオブジェクトのタイプを返します.
     * @return int
     */
    public function getType()
    {
        return $this->original->getType();
    }
    
    /**
     * ラップ対象のオブジェクトの before メソッドを実行します.
     * @param  Peach_DT_Time $time
     * @return bool
     */
    public function before(Peach_DT_Time $time)
    {
        return $this->original->before($time);
    }
    
    /**
     * ラップ対象のオブジェクトの after メソッドを実行します.
     * @param  Peach_DT_Time $time
     * @return bool
     */
    public function after(Peach_DT_Time $time)
    {
        return $this->original->after($time);
    }
    
    /**
     * ラップ対象のオブジェクトの compareTo メソッドを実行します.
     * @param  Peach_DT_Time $subject
     * @return bool
     */
    public function compareTo($subject)
    {
        return $this->original->compareTo($subject);
    }
    
    /**
     * 指定されたインスタンスをラップする新しい TimeWrapper を構築します.
     * 
     * @param  Peach_DT_Time $instance ラップ対象のオブジェクト
     * @return Peach_DT_TimeWrapper
     * @codeCoverageIgnore
     */
    protected function newInstance(Peach_DT_Time $instance)
    {
        return new self($instance);
    }
    
    /**
     * ラップ対象のオブジェクトの add メソッドを実行し, その返り値をこのクラスでラップします.
     * 
     * @param  string $field
     * @param  int    $amount
     * @return Peach_DT_TimeWrapper
     */
    public function add($field, $amount)
    {
        return $this->newInstance($this->original->add($field, $amount));
    }
    
    /**
     * ラップ対象のオブジェクトの set メソッドを実行し, その返り値をこのクラスでラップします.
     * 
     * @param  string $field
     * @param  int    $value
     * @return Peach_DT_TimeWrapper
     */
    public function set($field, $value)
    {
        return $this->newInstance($this->original->set($field, $value));
    }
    
    /**
     * ラップ対象のオブジェクトの setAll メソッドを実行し, その返り値をこのクラスでラップします.
     * @param  array|ArrayMap $subject
     * @return Peach_DT_TimeWrapper
     */
    public function setAll($subject)
    {
        return $this->newInstance($this->original->setAll($subject));
    }
    
    /**
     * ラップ対象のオブジェクトの get メソッドを実行します.
     * 
     * @param  string $field
     * @return int
     */
    public function get($field)
    {
        return $this->original->get($field);
    }
    
    /**
     * ラップ対象のオブジェクトの format メソッドを実行します.
     * @param  Peach_DT_Format $format
     * @return string
     */
    public function format(Peach_DT_Format $format = null)
    {
        return $this->original->format($format);
    }
    
    /**
     * ラップ対象のオブジェクトの formatTime メソッドを実行します.
     * @return string
     */
    public function formatTime()
    {
        return $this->original->formatTime();
    }
    
    /**
     * 指定されたオブジェクトとこのオブジェクトを比較します.
     * compareTo による比較結果が 0 を返し, かつクラスが同じ場合に TRUE を返します.
     *
     * @param  mixed   $obj 比較対象のオブジェクト
     * @return boolean      二つのオブジェクトが等しい場合に TRUE, それ以外は FALSE
     */
    public function equals($obj)
    {
        if (get_class($this) != get_class($obj)) {
            return false;
        }
        return $this->compareTo($obj) === 0;
    }
    
    /**
     * ラップ対象のオブジェクトの getDateCount メソッドを実行します.
     * @return int
     */
    public function getDateCount()
    {
        return $this->original->getDateCount();
    }
    
    /**
     * ラップ対象のオブジェクトの getDay メソッドを実行します.
     * @return int
     */
    public function getDay()
    {
        return $this->original->getDay();
    }
    
    /**
     * ラップ対象のオブジェクトの isLeapYear メソッドを実行します.
     * @return bool
     */
    public function isLeapYear()
    {
        return $this->original->isLeapYear();
    }
    
    /**
     * ラップ対象のオブジェクトの toDate メソッドを実行します.
     * @return Peach_DT_Time
     */
    public function toDate()
    {
        return $this->original->toDate();
    }
    
    /**
     * ラップ対象のオブジェクトの toDatetime メソッドを実行します.
     * @return Peach_DT_Time
     */
    public function toDatetime()
    {
        return $this->original->toDatetime();
    }
    
    /**
     * ラップ対象のオブジェクトの toTimestamp メソッドを実行します.
     * @return Peach_DT_Time
     */
    public function toTimestamp()
    {
        return $this->original->toTimestamp();
    }
    
    /**
     * ラップ対象のオブジェクトの __toString メソッドを実行します.
     * @param  Peach_DT_Time $time
     * @return string
     */
    public function __toString()
    {
        return $this->original->__toString();
    }
}
