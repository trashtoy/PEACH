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
/**
 * @package DF
 * @ignore
 */
/**
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_Context
{
    /**
     *
     * @var Peach_DF_Utf8Codec;
     */
    private $utf8Codec;
    
    /**
     * 出力方法をカスタマイズするためのオプションです.
     * @var Peach_Util_ArrayMap
     */
    private $options;
    
    /**
     * decode 対象の Unicode 文字列 (Unicode 符号点の配列) です.
     * @var array
     */
    private $unicodeList;
    
    /**
     * メンバ変数 $text のバイト数です.
     * @var int
     */
    private $count;
    
    /**
     * 現在の index です.
     * @var int
     */
    private $index;
    
    /**
     * 現在の index が参照している文字です.
     * @var string
     */
    private $current;
    
    /**
     * 現在の行番号です. decode に失敗した際にエラーの発生場所を提示するために使用します.
     * @var int 
     */
    private $row;
    
    /**
     * 現在の列番号です. decode に失敗した際にエラーの発生場所を提示するために使用します.
     * @var int 
     */
    private $col;
    
    /**
     * 
     * @param string $text
     */
    public function __construct($text, Peach_Util_ArrayMap $options)
    {
        $this->utf8Codec   = new Peach_DF_Utf8Codec();
        $this->unicodeList = $this->utf8Codec->decode($text);
        $this->options     = $options;
        $this->count       = count($this->unicodeList);
        $this->index       = 0;
        $this->row         = 1;
        $this->col         = 1;
        $this->current     = $this->computeCurrent();
    }
    
    /**
     * 指定されたオプションが ON かどうかを調べます.
     * 
     * @param  int $code オプション (JsonCodec で定義されている定数)
     * @return bool      指定されたオプションが ON の場合は true, それ以外は false
     */
    public function getOption($code)
    {
        return $this->options->get($code, false);
    }
    
    /**
     * 
     * @return bool
     */
    public function hasNext()
    {
        return ($this->index < $this->count);
    }
    
    /**
     * 
     * @return string
     */
    private function computeCurrent()
    {
        if (!$this->hasNext()) {
            return null;
        }
        $index   = $this->index;
        $current = $this->encodeIndex($index);
        if ($current === "\r" && $this->encodeIndex($index + 1) === "\n") {
            return "\r\n";
        }
        return $current;
    }
    
    /**
     * 
     * @param int $point
     * @return string
     */
    public function encodeCodepoint($point)
    {
        return $this->utf8Codec->encode($point);
    }
    
    private function encodeIndex($index)
    {
        return ($index < $this->count) ? $this->encodeCodepoint($this->unicodeList[$index]) : null;
    }
    
    public function current()
    {
        return $this->current;
    }
    
    /**
     * @return int 現在の文字の Unicode 符号点. もしも現在の文字が存在しない場合は null
     */
    public function currentCodePoint()
    {
        return $this->hasNext() ? $this->unicodeList[$this->index] : null;
    }
    
    /**
     * 
     * @param string $message
     * @throws DecodeException
     */
    public function throwException($message)
    {
        throw new Peach_DF_JsonCodec_DecodeException("{$message} at line {$this->row}, column {$this->col}");
    }
    
    /**
     * index を 1 進めます. 次の文字を返します.
     * @return string
     */
    public function next()
    {
        static $breakCode = array("\r", "\n", "\r\n");
        if (!$this->hasNext()) {
            $this->throwException("Cannnot read next");
        }
        $result = $this->current;
        if (in_array($result, $breakCode)) {
            $this->row++;
            $this->col = 1;
        } else {
            $this->col++;
        }
        $this->index += ($result === "\r\n") ? 2 : 1;
        $current = $this->computeCurrent();
        $this->current = $current;
        return $current;
    }
    
    /**
     * この Context の現在位置から, 指定された文字数分の文字列を返します.
     * @param  int $count
     * @return string
     */
    public function getSequence($count)
    {
        $result = $this->current;
        for ($i = 1; $i < $count; $i++) {
            $result .= $this->encodeIndex($this->index + $i);
        }
        return $result;
    }
    
    /**
     * 指定された文字数だけ index を進めます.
     * @param int $count 文字数
     */
    public function skip($count)
    {
        if ($this->count - $this->index < $count) {
            $this->throwException("Cannot skip {$count} characters");
        }
        
        $this->index += $count;
        $this->current = $this->computeCurrent();
    }
}