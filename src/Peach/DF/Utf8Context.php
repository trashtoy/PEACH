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
 * UTF-8 文字列を Unicode 符号点に変換します.
 * このクラスは Utf8Codec からのみ使用されることを想定しています.
 * エンドユーザーがこのクラスのインスタンスを直接操作する機会はありません.
 * 
 * @ignore
 */
class Peach_DF_Utf8Context
{
    /**
     * decode 対象の UTF-8 文字列です.
     * @var string
     */
    private $text;
    
    /**
     * decode 対象の文字列のバイト長です.
     * @var int
     */
    private $length;
    
    /**
     * 現在のバイト位置です. (0 以上 $length 以下)
     * @var int
     */
    private $index;
    
    /**
     * 指定された UTF-8 文字列を処理する Utf8Context インスタンスを構築します.
     * @param string $text 解析対象の UTF-8 文字列
     */
    public function __construct($text)
    {
        $this->text   = $text;
        $this->length = strlen($text);
        $this->index  = 0;
    }
    
    /**
     * index を 1 進めると同時に次の文字を返します.
     * 次の index が存在しない場合は null を返します.
     * 
     * @return int 現在の文字の Unicode 符号点
     */
    public function next()
    {
        if (!$this->hasNext()) {
            return null;
        }
        
        $first = substr($this->text, $this->index, 1);
        $byte  = ord($first);
        
        // ASCII の範囲の文字はそのまま返します
        if ($byte < 128) {
            $this->index++;
            return $byte;
        }
        
        // ビットが 10xxxxxx または 1111111* の文字は NG なのでスキップして次の文字に進みます
        if ($byte < 192 || 254 <= $byte) {
            $this->index++;
            return $this->next();
        }
        
        return $this->nextMultibyteChar($byte);
    }
    
    /**
     * 次の文字の Unicode 符号点を返します.
     * 解析対象の文字列が途中で切れている場合
     * (例えば次の 1 文字が 3 バイトであるにも関わらず, 解析対象の文字列が残り 2 バイトしかない等)
     * 足りないバイト列を 0x80 で補います.
     * 
     * @param  int $byte 現在の文字のはじめの 1 byte
     * @return int 現在の文字の Unicode 符号点
     */
    private function nextMultibyteChar($byte)
    {
        $count  = $this->getCharCount($byte);
        $next   = substr($this->text, $this->index, $count);
        $length = strlen($next);
        if ($length < $count) {
            $next .= str_repeat(chr(0x80), $count - $length);
        }
        
        $code = $this->getUnicodeNumber($next);
        if ($code === null) {
            $this->index++;
            return $this->next();
        }
        $this->index += $length;
        return $code;
    }
    
    /**
     * 次の文字が存在するかどうかを調べます.
     * 
     * @return bool 次の文字が存在する場合は true
     */
    public function hasNext()
    {
        return ($this->index < $this->length);
    }
    
    /**
     * 次の 1 文字が何バイトで構成されているかを計算します.
     * 引数に応じて以下の結果を返します. (以下 2 進数表示)
     * 
     * <pre>
     * 1111110* => 6
     * 111110** => 5
     * 11110*** => 4
     * 1110**** => 3
     * 110***** => 2
     * </pre>
     * 
     * @param  int $byte 次の文字の最初の 1 バイト
     * @return int       次の文字のバイト数
     */
    private function getCharCount($byte)
    {
        for ($i = 0; $i < 7; $i ++) {
            $bit = pow(2, 7 - $i); // 128, 64, 32, 16 ...
            if ($byte < $bit) {
                return max(1, $i);
            }
            $byte %= $bit;
        }
        
        // @codeCoverageIgnoreStart
        throw new Exception("Invalid State Error");
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * 指定されたバイト列を Unicode 符号点に変換します.
     * 引数が不正なバイト列だった場合は null を返します.
     * 
     * @param  string $str 次の 1 文字のバイト列
     * @return int         次の文字の Unicode 符号点
     */
    private function getUnicodeNumber($str)
    {
        $first  = ord($str);
        $length = strlen($str);
        $div    = pow(2, 7 - $length);
        
        // UNICODE の数値
        $bit = $first % $div;
        for ($i = 0; $i < $length - 1; $i ++) {
            $code = ord(substr($str, $i + 1, 1));
            // 2バイト目以降のビットが 10xxxxxx でない場合は不正なパターンとする
            if (intval($code / 64) !== 2) {
                return null;
            }
            $bit *= 64;
            $bit += $code % 64;
        }
        
        return $bit;
    }
}
