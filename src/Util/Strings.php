<?php
/** @package Util */
/**
 * 文字列処理に関するユーティリティクラスです
 * 
 * @package Util
 */
class Util_Strings {
    /**
     * このクラスはインスタンス化することができません.
     */
    private function __construct() {}
    
    /**
     * 内部関数の {@link explode() explode()} のカスタム版です. 
     * 基本的にはオリジナルの explode() と同じですが, 以下の点が異なります.
     * 
     * - $separator が空文字列の場合に空の配列を返す (オリジナルは FALSE を返す)
     * - $value が文字列以外の場合, {@link Util_Values::stringValue()} の結果を使用する
     *   (オリジナルは単純に string にキャストした結果を使うため, 
     *    オブジェクトを引数に指定した時の挙動が PHP 5.1 と 5.2 以降で異なる)
     * 
     * 引数をどのように指定しても, 返り値が必ず配列型になることが特徴です.
     * 
     * @param  string $separator セパレータ
     * @param  string $value     対象文字列
     * @return array  セパレータが見つからないときは長さ 1 の配列, 
     *                対象が空文字列の場合は空の配列, それ以外は explode() と同じ.
     */
    public static function explode($separator, $value) {
        if (!is_string($value)) {
            return self::explode($separator, Util_Values::stringValue($value));
        }
        else if (strlen($separator)) {
            return explode($separator, $value);
        }
        else {
            return array();
        }
    }
    
    /**
     * 指定された文字列を行単位で分割します.
     * 引数の文字列を CR, LF, CRLF で分割し, 結果の配列を返します.
     * 結果の配列の各要素に改行コードは含まれません.
     * 
     * @param  string 分割対象の文字列
     * @return array  行単位で分割された文字列の配列
     */
    public static function getLines($str) {
        return preg_split("/\\r\\n|\\r|\\n/", $str);
    }
    
    /**
     * 指定された文字列が空白文字の集合からなる文字列かどうかを返します.
     * @param  string
     * @return boolean 引数が NULL, 空文字列, "\r", "\n", "\t", 
     *                 半角スペースから成る文字列の場合に TRUE, それ以外は FALSE
     */
    public static function isWhitespace($str) {
        return !strlen($str) || !preg_match("/[^\\s]/", $str);
    }
    
    /**
     * 指定された文字列を基底ディレクトリに変換します.
     * 引数が空文字列か, '/' で終わる文字列の場合は引数をそのまま返します.
     * それ以外の場合は, 引数の末尾に '/' を連結した文字列を返します.
     * 
     * @param  string 変換対象の文字列
     * @return string 基底ディレクトリ名
     */
    public static function basedir($basedir) {
        if (!is_string($basedir)) {
            return self::basedir(Util_Values::stringValue($basedir));
        }
        else if (!strlen($basedir)) {
            return "";
        }
        else if (substr($basedir, -1) === "/") {
            return $basedir;
        }
        else {
            return $basedir . "/";
        }        
    }
    
    /**
     * 指定された文字列の中で、"\" によるエスケープ処理のされていない文字列があらわれる
     * 最初のインデックスを返します.
     * 偶数個の "\" が続いた後に対象の文字列が出現した場合に、そのインデックスを返り値とします.
     * 奇数個の "\" の後の文字については, その直前の "\" によってエスケープされているとみなして
     * スルーします. 以下に例を示します.
     * 
     * <pre>
     * getRawIndex("AB=CD=EF", "=") : 2
     * getRawIndex("AB\\=CD=EF", "=") : 6
     * getRawIndex("AB\\\\=CD=EF", "=") : 4
     * </pre>
     * 
     * インデックスが存在しない場合は FALSE を返します.
     * 
     * @param  string 検索文字列
     * @param  string 検索対象の文字
     * @return int    インデックス. ただし存在しない場合は FALSE
     */
    public static function getRawIndex($text, $chr) {
        $chr = str_replace("\\", "\\\\", $chr);
        $pattern = "/(?<!\\\\)(?:\\\\\\\\)*(" . $chr . ".*)$/";
        preg_match($pattern, $text, $result);
        if (count($result)) {
            return strlen($text) - strlen($result[1]);
        }
        else {
            return FALSE;
        }
    }
    
    /**
     * ある文字列が指定された文字列で始まっているかどうかを判別します.
     * $prefix が空文字列の場合は TRUE を返します.
     * 引数が文字列以外の場合は {@link Util_Strings::stringValue()} が適用されます.
     * 
     * @param  string 検査対象の文字列
     * @param  string 開始する文字列
     * @return bool   引数 $text の先頭が $prefix である場合に TRUE
     */
    public static function startsWith($text, $prefix) {
        if (!is_string($text)) {
            return self::startsWith(Util_Values::stringValue($text), $prefix);
        }
        else if (!is_string($prefix)) {
            return self::startsWith($text, Util_Values::stringValue($prefix));
        }
        else if ($prefix === "") {
            return TRUE;
        }
        else {
            return (strpos($text, $prefix) === 0);
        }
    }
    
    /**
     * ある文字列が指定された文字列で終了しているかどうかを判別します.
     * $suffix が空文字列の場合は TRUE を返します.
     * 引数が文字列以外の場合は {@link Util_Values::stringValue()} が適用されます.
     * 
     * @param  string 検査対象の文字列
     * @param  string 終了する文字列
     * @return bool   引数 $text の末尾が $suffix に等しい場合に TRUE
     */
    public static function endsWith($text, $suffix) {
        if (!is_string($text)) {
            return self::endsWith(Util_Values::stringValue($text), $suffix);
        }
        else if (!is_string($suffix)) {
            return self::endsWith($text, Util_Values::stringValue($suffix));
        }
        else if ($suffix === "") {
            return TRUE;
        }
        else {
            $index = strlen($text) - strlen($suffix);
            return substr($text, $index) === $suffix;
        }
    }
    
    /**
     * ある文字列が指定された文字で終了して, かつエスケープ処理されていないかを判別します.
     * 以下に例を示します.
     * 
     * - ("[ABC]", "]") => TRUE
     * - ("[ABC\\]", "]") => FALSE ("\\" がその後ろの "]" をエスケープしているとみなされる)
     * - ("[ABC\\\\]", "]") => TRUE ("\\\\" が一つの文字として扱われるため, 直後の "]" に影響しない)
     * 
     * @param  string 検査対象の文字列
     * @param  string 検査対象の文字
     * @return bool   引数 $text の末尾が, '\' でエスケープされていない $chr で終了している場合のみ TRUE
     */
    public static function endsWithRawChar($text, $chr) {
        $chr = str_replace("\\", "\\\\", $chr);
        $pattern = "/(?<!\\\\)(?:\\\\\\\\)*(" . $chr . ")$/";
        $result = preg_match($pattern, $text);
        return (0 < $result);
    }
    
    /**
     * 文字列内に含まれる {0}, {1}, {2} などのテンプレート変数を, $args 内の各要素で置き換えます. 例えば
     * <code>template('My name is {0}. I am {1} years old', array('Taro', 18))</code>
     * の結果は次のようになります.
     * <code>"My name is Taro. I am 18 years old"</code>
     * 
     * $template が NULL の場合は NULL を返します.
     * 
     * @param  string テンプレート
     * @param  array  置き換える内容の配列
     * @return string テンプレートの適用結果
     */
    public static function template($template, array $args = array()) {
        if (!isset($template)) {
            return NULL;
        }
        $template = Util_Values::stringValue($template);
        $replaces = array();
        for ($i = 0; $i < count($args); $i ++) {
            $from = "{" . $i . "}";
            $to   = $args[$i];
            $replaces[$from] = $to;
        }
        return strtr($template, $replaces);
    }
}
?>