<?php
/** @package Util */
/** */
require_once(dirname(__FILE__) . "/Equator.php");

/**
 * デフォルトの Equator です.
 * @package Util
 */
class Util_DefaultEquator implements Util_Equator {
    /** このクラスはインスタンス化できません. */
    private function __construct() {}
    
    /**
     * このクラスの唯一のインスタンスを返します.
     * 
     * @return Util_DefaultEquator
     */
    public static function getInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * 引数 $var1 と $var2 を比較します.
     * 
     * 引数の少なくともいずれか一方がオブジェクトまたは配列の場合は
     * print_r() の結果を比較します.
     * (hashCode() メソッドで print_r() を使ってハッシュコードを生成するためです.)
     * 
     * 引数の少なくともいずれか一方が文字列の場合は両引数を文字列として比較します.
     * 引数が両方とも数値の場合は == で,
     * それ以外の場合は === で比較した結果を返します.
     * 
     * @param  mixed $var1
     * @param  mixed $var2
     * @return bool
     */
    public function equate($var1, $var2) {
        if ($var1 === $var2) {
            return TRUE;
        }
        else if (is_object($var1) || is_object($var2) || is_array($var1) || is_array($var2)) {
            $str1 = print_r($var1, TRUE);
            $str2 = print_r($var2, TRUE);
            return $str1 === $str2;
        }
        else if (is_string($var1) || is_string($var2)) {
            return strcmp($var1, $var2) === 0;
        }
        else if (is_numeric($var1) && is_numeric($var2)) {
            return $var1 == $var2;
        }
        else {
            return $var1 === $var2;
        }
    }
    
    /**
     * 指定された値のハッシュ値を返します.
     * ハッシュ値の計算規則は以下のとおりです.
     * 
     * - boolean 型にキャストした結果 FALSE となるような引数については 0
     * - 引数が数値表現の場合はその絶対値
     * - それ以外の値の場合は, 引数の文字列表現の {@link md5() md5()} ハッシュの一部
     * 
     * @param  mixed $var
     * @return int   引数のハッシュ値
     */
    public function hashCode($var) {
        if (empty($var)) {
            return 0;
        }
        else if (is_numeric($var)) {
            return abs(intval($var));
        }
        else {
            $str = (is_object($var) || is_array($var)) ? print_r($var, TRUE) : strval($var);
            return hexdec(substr(md5($str), 22));
        }
    }
}
?>