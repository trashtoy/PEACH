<?php
/** @package Util */
/**
 * 相互に比較出来ることをあらわすインタフェースです. 
 * 
 * このインタフェースは, {@link Util_Arrays} のソート・大小比較関連の API で使用されます.
 * Java における
 * {@link http://java.sun.com/j2se/1.5.0/ja/docs/ja/api/java/lang/Comparable.html java.lang.Comparable} 
 * と同じ用途で使われることを想定しています.
 * 
 * @package Util
 */
interface Util_Comparable {
    /**
     * このオブジェクトと引数の値を比較します.
     * 
     * このオブジェクトが $subject より小さい場合は負の整数, 
     * このオブジェクトが $subject より大きい場合は正の整数, 
     * このオブジェクトと $subject が等しい場合は 0 を返します.
     * 
     * もしも このオブジェクトが $subject と比較できない場合は
     * NULL を返すか, または任意の例外をスローします.
     * 
     * @param  mixed $subject 比較対象の値
     * @return int            比較結果をあらわす整数
     */
    public function compareTo($subject);
}
?>