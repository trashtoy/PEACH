<?php
/** @package Util */
/**
 * マップに含まれるキーと値のペアをあらわすインタフェースです.
 * {@link Util_Map::entryList()} の返り値として使用されます.
 * 
 * @package Util
 */
interface Util_MapEntry {
    /**
     * このエントリーのキーです.
     * 
     * @return mixed このエントリーのキー
     */
    public function getKey();
    
    /**
     * このエントリーの値です.
     * 
     * @return mixed このエントリーの値.
     */
    public function getValue();
    
    /**
     * このエントリーの値を新しい値に更新します. 更新はもとの Map に反映されます.
     * ただし, このエントリーがもとのマップから削除されている場合は
     * このメソッドの動作は定義されません.
     * 
     * @param mixed $value 新しい値
     */
    public function setValue($value);
}
?>