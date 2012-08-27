<?php
/** 
 * 時間関連のユーティリティクラスです. 
 * 
 * @package DT
 */
class DT_Util {
    /**
     * このクラスはインスタンス化できません.
     */
    private function __construct() {}
    
    /**
     * 2つの時間オブジェクトを比較します. 一つめの引数のほうが後 (未来) の場合は正の値,
     * 前 (過去) の場合は負の値, 同じ場合は 0 を返します.
     * 
     * このメソッドは, 一つ目の時間オブジェクトの {@link DT_Time::compareTo()} メソッドを呼び出し
     * 二つ目の時間オブジェクトと比較した結果を返します.
     *
     * @param  DT_Time $time1 1つ目の時間
     * @param  DT_Time $time2 2つ目の時間
     * @return int            1つ目の時間を2つ目の時間と比較した結果
     * @see    DT_Time::compareTo()
     */
    public static function compareTime(DT_Time $time1, DT_Time $time2) {
        return $time1->compareTo($time2);
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最も古いものを返します.
     * 引数に DT_Time 型オブジェクトを羅列するか, DT_Time 型オブジェクトの配列を指定してください.
     * 引数に DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param array|DT_Time $time...
     * @return DT_Time      引数の中で最も古い DT_Time オブジェクト. 
     *                       引数が空か, DT_Time オブジェクトが含まれていない場合は NULL
     */
    public static function oldest() {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Util_Arrays::min(Util_Arrays::pickup($args, "DT_Time"));
    }
    
    /**
     * 引数に渡された時間オブジェクトの中で最新のものを返します. 
     * 引数に DT_Time 型オブジェクトを羅列するか, DT_Time 型オブジェクトの配列を指定してください.
     * 引数に DT_Time が一つも存在しない場合は NULL を返します.
     * 
     * @param  array|DT_Time $time...
     * @return DT_Time       引数の中で最新の DT_Time オブジェクト. 
     *                        引数が空か, DT_Time オブジェクトが含まれていないの場合は NULL
     */
    public static function latest() {
        $args = func_get_args();
        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }
        return Util_Arrays::max(Util_Arrays::pickup($args, "DT_Time"));
    }
}
?>
