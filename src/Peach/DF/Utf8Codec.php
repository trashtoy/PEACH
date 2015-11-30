<?php
/*
 * Copyright (c) 2015 @trashtoy
 * https://github.com/trashtoy/
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the
 * Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
/** @package DF */
/**
 * UTF-8 で符号化された文字列を扱う Codec です.
 * このクラスの decode メソッドは, UTF-8 の文字列を文字単位で分解し,
 * 各文字の Unicode 符号点をあらわす整数の配列を返します.
 * encode メソッドは, 整数の配列を UTF-8 の文字列に変換します.
 * 
 * UTF-8 のビットパターンは以下の通りです.
 * <pre>
 * 0xxxxxxx                                               (00-7f)
 * 110xxxxx 10xxxxxx                                      (c0-df)(80-bf)
 * 1110xxxx 10xxxxxx 10xxxxxx                             (e0-ef)(80-bf)(80-bf)
 * 11110xxx 10xxxxxx 10xxxxxx 10xxxxxx                    (f0-f7)(80-bf)(80-bf)(80-bf)
 * 111110xx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx           (f8-fb)(80-bf)(80-bf)(80-bf)(80-bf)
 * 1111110x 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx 10xxxxxx  (fc-fd)(80-bf)(80-bf)(80-bf)(80-bf)(80-bf)
 * </pre>
 * 
 * RFC 3629 では 5 バイト以上のシーケンスが無効とされましたが, 
 * このクラスは RFC 2279 に基づいて 5 バイト以上のシーケンスも受理します.
 * なお, このクラスはサロゲートペアを考慮しません.
 * 
 * 引用文献: {@link http://ja.wikipedia.org/wiki/UTF-8 UTF-8 - Wikipedia}
 * 
 * このクラスは将来的に状態 (メンバ変数) を持つ可能性が高いので,
 * 敢えて Singleton パターンにしていません.
 * 
 * @package DF
 */
class Peach_DF_Utf8Codec implements Peach_DF_Codec
{
    /**
     * 指定された UTF-8 の文字列を Unicode 符号点の配列に変換します.
     * 
     * @param  string $text UTF-8 でエンコードされた文字列
     * @return array        Unicode 符号点の配列
     */
    public function decode($text)
    {
        $bom = chr(0xEF) . chr(0xBB) . chr(0xBF);
        if (Peach_Util_Strings::startsWith($text, $bom)) {
            return $this->decode(substr($text, 3));
        }
        
        $context = new Peach_DF_Utf8Context($text);
        $result  = array();
        while ($context->hasNext()) {
            $result[] = $context->next();
        }
        
        // 文字列の末尾に不正な文字が存在していた場合,
        // $result の最後の要素に null が代入されるので取り除く
        $count = count($result);
        if ($count && $result[$count - 1] === null) {
            array_pop($result);
        }
        return $result;
    }
    
    /**
     * 指定された Unicode 符号点の配列を UTF-8 文字列に変換します.
     * 引数には Unicode 符号点をあらわす正の整数の配列を指定してください.
     * 配列以外の値を指定した場合は, その引数 (整数でない場合はメソッド内で整数に変換されます)
     * を Unicode 符号点とみなし, 1 文字分の UTF-8 文字列を返します.
     * 
     * @param  array|int $var Unicode 符号点の配列
     * @return string UTF-8 文字列
     */
    public function encode($var)
    {
        return is_array($var) ? array_reduce($var, array($this, "appendChar"), "") : $this->encodeUnicode($var);
    }
    
    /**
     * 指定された文字列の末尾に, 引数の Unicode 符号点を UTF-8 に変換したバイト列を追加します.
     * 
     * @param string $result
     * @param int    $unicode
     * @ignore
     */
    public function appendChar($result, $unicode)
    {
        return $result . $this->encodeUnicode($unicode);
    }
    
    /**
     * 指定された Unicode 符号点を表現する 1 文字分の UTF-8 文字列を返します.
     * @param  int $unicode Unicode 符号点
     * @return string 引数の Unicode 文字を表現する UTF-8 文字列
     */
    private function encodeUnicode($unicode)
    {
        if (!is_int($unicode)) {
            return $this->encodeUnicode(intval($unicode));
        }
        if ($unicode < 0 || 0xFFFF < $unicode) {
            return $this->encodeUnicode(max(0, $unicode % 0x200000));
        }
        
        $count = $this->getCharCount($unicode);
        if ($count === 1) {
            return chr($unicode);
        }
        
        $result = array();
        for ($i = 1; $i < $count; $i++) {
            array_unshift($result, 0x80 + $unicode % 64); // Last 6 bit
            $unicode >>= 6;
        }
        array_unshift($result, $this->getFirstCharPrefix($count) + $unicode);
        return implode("", array_map("chr", $result));
    }
    
    /**
     * 指定された Unicode 符号点が UTF-8 において何バイトで表現されるか調べます.
     * @param  int $unicode Unicode 符号点
     * @return int バイト数
     */
    private function getCharCount($unicode)
    {
        static $borders = array(
            1 => 0x80,       //  7 bit
            2 => 0x800,      // 11 bit
            3 => 0x10000,    // 16 bit
            4 => 0x200000,   // 21 bit
        );
        foreach ($borders as $i => $border) {
            if ($unicode < $border) {
                return $i;
            }
        }
        // @codeCoverageIngoreStart
        throw new Exception("Illegal state");
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * 引数の値に応じて以下の値を返します. (2 進数表現)
     * 
     * - 1: 00000000
     * - 2: 11000000
     * - 3: 11100000
     * - 4: 11110000
     * - 5: 11111000
     * - 6: 11111100
     * 
     * @param  int $count バイト数
     * @return int 引数のバイト数に応じた値
     */
    private function getFirstCharPrefix($count)
    {
        $result = 0;
        for ($i = 0; $i < $count; $i++) {
            $result >>= 1;
            $result += 0x80;
        }
        return $result;
    }
}
