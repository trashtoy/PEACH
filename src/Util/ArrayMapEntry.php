<?php
/** 
 * @package Util
 * @ignore
 */
/** */
require_once(dirname(__FILE__) . "/AbstractMapEntry.php");

/**
 * {@link ArrayMap} の entryList() から生成されるオブジェクトです.
 * 
 * @package Util
 */
class Util_ArrayMapEntry extends Util_AbstractMapEntry {
    /**
     * このエントリーが登録されている ArrayMap です.
     * @var Util_ArrayMap
     */
    private $map;
    
    /**
     * 新しい ArrayMapEntry を構築します.
     * 
     * @param mixed         $key   キー
     * @param mixed         $value キーに関連づけられた値
     * @param Util_ArrayMap $map   このエントリーが属する ArrayMap
     */
    public function __construct($key, $value, Util_ArrayMap $map) {
        parent::__construct($key, $value);
        $this->map = $map;
    }
    
    /**
     * このエントリーの値を更新します.
     * @param mixed $value 新しくセットされる値
     */
    public function setValue($value) {
        $this->map->put($this->key, $value);
        $this->value = $value;
    }
    
    /**
     * この MapEntry の文字列表現です.
     * @return string
     */
    public function __toString() {
        return "[{$this->key}={$this->value}]";
    }
}
?>