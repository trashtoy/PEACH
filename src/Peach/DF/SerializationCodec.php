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
 * {@link http://php.net/manual/function.serialize.php serialize()} と
 * {@link http://php.net/manual/function.unserialize.php unserialize()}
 * を利用して, resource 型以外の任意の値を文字列に変換したり,
 * 文字列から値を復元したりするための Codec です.
 * 
 * @package DF
 */
class Peach_DF_SerializationCodec implements Peach_DF_Codec
{
    /**
     * このクラスはシングルトンです. 直接インスタンス化することは出来ません.
     */
    private function __construct() {}
    
    /**
     * このクラスのインスタンスを取得します.
     * @return Peach_DF_SerializationCodec
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        static $instance = null;
        if (!isset($instance)) {
            $instance = new self();
        }
        return $instance;
    }
    
    /**
     * serialize された値から元の値を復元します.
     * 
     * @param  string $text serialize された文字列
     * @return mixed        復元された値
     */
    public function decode($text)
    {
        return unserialize($text);
    }
    
    /**
     * 指定された値を serialize した結果を返します.
     * @param  mixed  $var serialize 対象の値
     * @return string      serialize した結果
     */
    public function encode($var)
    {
        return serialize($var);
    }
}
