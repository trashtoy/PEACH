<?php
/**
 * @package DT 
 * @ignore
 */
/**
 * 日時の値調整を行うクラスです.
 * このクラスは DT_Date, DT_Datetime, DT_Timestamp の初期化処理のみで使用されることを
 * 想定しています.
 * 
 * @package DT
 * @ignore
 */
class DT_FieldAdjuster {
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
    public function __construct($key, $upperKey, $min, $max) {
        $this->key      = $key;
        $this->upperKey = $upperKey;
        $this->min      = $min;
        $this->max      = $max;
    }
    
    /**
     * 指定された値の繰り上がり処理を行います.
     * この関数は DT_Time::adjust() から呼び出されます.
     * 
     * @param Util_Map $fields 調整対象のフィールド一覧
     * @ignore
     */
    public function moveUp(Util_Map $fields) {
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
     * この関数は DT_Time::adjust() から呼び出されます.
     * 
     * @param Util_Map $fields 調整対象のフィールド一覧
     * @ignore
     */
    public function moveDown(Util_Map $fields) {
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
        $fields->put($key, $max - ($min - $field  - 1) % $range);
    }
}
?>