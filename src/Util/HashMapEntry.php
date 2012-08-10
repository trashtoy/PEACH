<?php 
/** 
 * @package Util 
 * @ignore
 */
/** */
require_once(dirname(__FILE__) . "/AbstractMapEntry.php");

/** 
 * {@link Util_HashMap} の内部で使われる MapEntry です.
 * 
 * @package Util
 * @see     Util_HashMap::createEntry()
 */
class Util_HashMapEntry extends Util_AbstractMapEntry {
    /**
     * このエントリーのキーと引数のキーを比較します.
     * このメソッドは {@link Util_HashMap::put()} または
     * {@link Util_HashMap::containsKey()} でキーが既に存在するかどうか調べるために使用されます.
     * 
     * 
     * @param  mixed $key 比較対象のキー
     * @param  Util_Equator $e 比較に使用する Equator
     * @return bool  このエントリーのキーと引数が等しい場合に TRUE
     */
    public function keyEquals($key, Util_Equator $e) {
        return $e->equate($this->key, $key);
    }
    
    /**
     * このエントリーの値を新しい値に更新します.
     * 
     * @param mixed 新しい値
     */
    public function setValue($value) {
        $this->value = $value;
    }
}
?>