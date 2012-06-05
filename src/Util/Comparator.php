<?php
/** @package Util */
/**
 * 与えられた二つの値を比較するインタフェースです. 
 * このインタフェースは, {@link Util_Arrays::sort()} の引数として使われます.
 * Java における 
 * {@link http://java.sun.com/javase/ja/6/docs/ja/api/java/util/Comparator.html java.util.Comparator}
 * と同じ用途で使われることを想定しています.
 * 
 * PHP には同様の働きをする {@link usort() usort()} などのビルトイン関数がありますが,
 * ユーザ定義関数にメンバメソッド (クラスメソッドではなく) を利用する仕組みがないため,
 * 必要に応じてこのインタフェースを利用してください.
 * 
 * @package Util
 */
interface Util_Comparator {
    /**
     * 二つの値を比較します.
     * 
     * $var1 が $var2 より小さい場合は負の整数, 
     * $var1 が $var2 より大きい場合は正の整数, 
     * $var1 と $var2 が等しい場合は 0 を返します.
     * 
     * もし $var1 と $var2 が比較できない場合は NULL を返すか, 
     * 任意の例外をスローします.
     * 
     * @param  mixed $var1 比較対象の値
     * @param  mixed $var2 比較対象の値
     * @return int         比較した結果, 
     *                     $var2 < $var1 の場合は正, 
     *                     $var1 < $var2 の場合は負, 
     *                     $var1 == $var2 の場合は 0
     */
    public function compare($var1, $var2);
}
?>