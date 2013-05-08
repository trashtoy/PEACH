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
/**
 * @package DT 
 * @ignore
 */
/**
 * 日時の値調整を行うクラスです.
 * このクラスは Peach_DT_Date, Peach_DT_Datetime, Peach_DT_Timestamp の初期化処理のみで使用されることを
 * 想定しています.
 * 
 * @package DT
 * @ignore
 */
class Peach_DT_FieldAdjuster
{
    /**
     * 調整対象のフィールドです.
     * @var int
     */
    private $key;

    /**
     * 調整対象フィールドの上位のフィールドです.
     * (例えば「時」に対する「日」、「秒」に対する「分」など
     * @var int
     */
    private $upperKey;

    /**
     * 調整対象フィールドがとりうる最小の値です.
     * @var int
     */
    private $min;

    /**
     * 調整対象フィールドがとりうる最大の値です.
     * @var int
     */
    private $max;

    /**
     * 新しい FieldAdjuster を構築します.
     * 
     * @param int $key
     * @param int $upperKey
     * @param int $min
     * @param int $max
     */
    public function __construct($key, $upperKey, $min, $max)
    {
        $this->key      = $key;
        $this->upperKey = $upperKey;
        $this->min      = $min;
        $this->max      = $max;
    }

    /**
     * 指定された値の繰り上がり処理を行います.
     * この関数は Peach_DT_Time::adjust() から呼び出されます.
     * 
     * @param Peach_Util_Map $fields 調整対象のフィールド一覧
     * @ignore
     */
    public function moveUp(Peach_Util_Map $fields)
    {
        $key        = $this->key;
        $upperKey   = $this->upperKey;
        $max        = $this->max;
        $min        = $this->min;
        $field      = $fields->get($key);
        $upperField = $fields->get($upperKey);
        if ($field <= $max) return;

        $range  = $max - $min + 1;
        $amount = intval(($field - $min) / $range);
        $fields->put($upperKey, $upperField + $amount);
        $fields->put($key, ($field - $min) % $range + $min);
    }

    /**
     * 指定された値の繰り下がり処理を行います.
     * この関数は Peach_DT_Time::adjust() から呼び出されます.
     * 
     * @param Peach_Util_Map $fields 調整対象のフィールド一覧
     * @ignore
     */
    public function moveDown(Peach_Util_Map $fields)
    {
        $key        = $this->key;
        $upperKey   = $this->upperKey;
        $max        = $this->max;
        $min        = $this->min;
        $field      = $fields->get($key);
        $upperField = $fields->get($upperKey);
        if ($min <= $field) return;

        $range  = $max - $min + 1;
        $amount = intval(($min - $field - 1) / $range) + 1;
        $fields->put($upperKey, $upperField - $amount);
        $fields->put($key, $max - ($min - $field - 1) % $range);
    }
}
?>