<?php
/*
 * Copyright (c) 2013 @trashtoy
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
/** @package Markup */
/**
 * コードを整形する際のインデント情報をあらわすクラスです.
 * @package Markup
 */
class Peach_Markup_Indent
{
    /**
     * 半角スペース 4 個です.
     * @var string
     */
    const SPACE4 = "    ";
    
    /**
     * タブ文字です.
     * @var string
     */
    const TAB    = "\t";
    
    /**
     * 改行コード CR です.
     * @var string
     */
    const CR     = "\r";
    
    /**
     * 改行コード LF です.
     * @var string
     */
    const LF     = "\n";
    
    /**
     * 改行コード CRLF です.
     * @var string
     */
    const CRLF   = "\r\n";
    
    /**
     * 
     * @var int
     */
    private $level;
    
    /**
     * 
     * @var string
     */
    private $unit;
    
    /**
     * 
     * @var string
     */
    private $breakCode;
    
    /**
     * 新しい Indent オブジェクトを構築します.
     * 
     * @param int    $level     インデントレベルの初期値です. デフォルトは 0 です. レベルは 0 未満でも構いません.
     * @param string $unit      インデント一つ分の文字列です. デフォルトは半角スペース 4 個です.
     * @param string $breakCode 改行コードです. デフォルトは CRLF です.
     */
    public function __construct($level = 0, $unit = self::SPACE4, $breakCode = self::CRLF)
    {
        $this->level     = $level;
        $this->unit      = $unit;
        $this->breakCode = $breakCode;
    }
    
    /**
     * インデント文字列を構築します.
     * @param  int $level
     * @return string
     */
    private function handleIndent($level)
    {
        return ($level < 1) ? "" : $this->handleIndent($level - 1) . $this->unit;
    }
    
    /**
     * 現在のインデントレベルを返します.
     * @return int インデントレベル
     */
    public function getLevel()
    {
        return $this->level;
    }
    
    /**
     * インデント一つ分の文字列です.
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
    
    /**
     * 現在のレベルでインデントします.
     * インデントレベルが 0 以下の場合は空文字列を返します.
     * @return string 現在のレベルのインデント文字列
     */
    public function indent()
    {
        return $this->handleIndent($this->level);
    }
    
    /**
     * 改行コードを返します.
     * @return string
     */
    public function breakCode()
    {
        return $this->breakCode;
    }
    
    /**
     * インデントレベルを一つ上げます.
     * 改行コードを返します.
     * @return string
     */
    public function stepUp()
    {
        $this->level ++;
        return $this->breakCode;
    }
    
    /**
     * インデントレベルを一つ下げます.
     * 新しいレベルのインデント文字列を返します.
     * 
     * @return string
     */
    public function stepDown()
    {
        $this->level --;
        return $this->indent($this->level);
    }
}
