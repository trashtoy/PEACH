<?php
/** @package Util */
/** */
require_once(dirname(__FILE__) . "/MapEntry.php");
/**
 * デフォルトの {@link Util_MapEntry} の実装です.
 * このクラスでは getKey(), getValue() のみ実装されています.
 * 
 * @package Util
 */
abstract class Util_AbstractMapEntry implements Util_MapEntry {
    /**
     * マッピングのキーです.
     * @var mixed
     */
    protected $key;
    
    /**
     * マッピングの値です.
     * @var mixed
     */
    protected $value;
    
    /**
     * 新しいエントリーオブジェクトを構築します.
     * @param mixed キー
     * @param mixed 値
     */
    public function __construct($key, $value) {
        $this->key   = $key;
        $this->value = $value;
    }
    
    /**
     * このエントリーのキーを返します.
     * @return mixed このエントリーのキー
     */
    public function getKey() {
        return $this->key;
    }
    
    /**
     * このエントリーの値を返します.
     * @return mixed このエントリーの値
     */
    public function getValue() {
        return $this->value;
    }
}
?>