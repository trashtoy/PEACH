<?php
/** @package Util */
/** */
require_once(dirname(__FILE__) . "/Comparator.php");

/**
 * 基底の Comparator の比較結果を逆にするための Comparator です.
 * 
 * @package Util
 */
class Util_ReverseComparator implements Util_Comparator {
    /**
     *
     * @var Util_Comparator
     */
    private $comparator;
    
    /**
     * 指定された比較条件を逆順にするための Comparator を構築します.
     * 引数が省略された場合は {@link Util_DefaultComparator} が使用されます.
     * 
     * @param Util_Comparator $comparator ソート条件
     */
    public function __construct(Util_Comparator $comparator = NULL) {
        $this->comparator = $comparator;
    }
    
    /**
     * この Comparator が内部で利用している, 基準の Comparator を返します.
     * @return Util_Comparator
     */
    public function getComparator() {
        return $this->comparator;
    } 
    
    /**
     * 引数の順番を逆にして, オリジナルの Comparator の compare() メソッドを実行します.
     * 
     * @param mixed $var1 比較対象の値
     * @param mixed $var2 比較対象の値
     */
    public function compare($var1, $var2) {
        return $this->comparator->compare($var2, $var1);
    }
}
?>