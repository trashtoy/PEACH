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
/** @package DT */
/**
 * 連続した数字文字列にマッチする Pattern です.
 * 
 * @ignore
 */
class Peach_DT_SimpleFormat_Numbers implements Peach_DT_SimpleFormat_Pattern
{
    /**
     * 適用先のフィールド名です.
     * @var string
     */
    private $fieldName;
    
    /**
     * マッチするパターン文字列です.
     * @var string
     */
    private $patternString;
    
    /**
     * フィールド名とパターン文字列を指定して, 新しい Numbers オブジェクトを生成します.
     * 
     * @param string $fieldName     適用対象のフィールド
     * @param string $patternString 正規表現
     * @codeCoverageIgnore
     */
    public function __construct($fieldName, $patternString)
    {
        $this->fieldName     = $fieldName;
        $this->patternString = $patternString;
    }
    
    /**
     * マッチした文字列を整数に変換した値を適用します.
     * 
     * @param Peach_Util_ArrayMap $fields  適用先のフィールド
     * @param string              $matched マッチした文字列
     */
    public function apply(Peach_Util_ArrayMap $fields, $matched)
    {
        $fields->put($this->fieldName, intval($matched));
    }
    
    /**
     * 文字列の先頭が, このオブジェクトにセットされている正規表現に合致しているかどうか調べます.
     * マッチした文字列を返します.
     * 
     * @param  string $input 検査対象の文字列
     * @return string        マッチした結果
     */
    public function match($input)
    {
        $matched = array();
        $test    = preg_match("/^{$this->patternString}/", $input, $matched);
        return $test ? $matched[0] : null;
    }
}
