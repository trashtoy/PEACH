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
 * 複数の Codec のエンコード・デコード処理を連結させるための Codec です.
 * 
 * このクラスはコンストラクタに 2 種類の Codec を指定してインスタンスを初期化します.
 * エンコードは, コンストラクタに指定した 1 番目, 2 番目の Codec の encode() を順に呼び出すことで実行されます.
 * デコードは, エンコードとは逆に 2 番目, 1 番目の順番で実行されます.
 * 
 * @package DF
 */
class Peach_DF_CodecChain implements Peach_DF_Codec
{
    /**
     * 1 番目の Codec です. エンコードの際に先に処理されます.
     * 
     * @var Codec
     */
    private $first;
    
    /**
     * 2 番目の Codec です. デコードの際に先に処理されます.
     * 
     * @var Codec
     */
    private $second;
    
    /**
     * 指定された Codec を使用して変換を行う CodecChain インスタンスを生成します.
     * 
     * もしも第 1 Codec が CodecChain インスタンスだった場合は以下のアルゴリズムに従ってチェーンの再構成を行います.
     * 
     * - 引数の CodecChain の第 1 Codec を新しいインスタンスの第 1 Codec として適用します
     * - 引数の CodecChain の第 2 Codec とコンストラクタ引数の第 2 Codec で新しい CodecChain を生成し, それを新しいインスタンスの第 2 Codec として適用します
     * 
     * @param Peach_DF_Codec $first  1 番目の Codec
     * @param Peach_DF_Codec $second 2 番目の Codec
     */
    public function __construct(Peach_DF_Codec $first, Peach_DF_Codec $second)
    {
        if ($first instanceof Peach_DF_CodecChain) {
            $this->first  = $first->first;
            $this->second = new self($first->second, $second);
        } else {
            $this->first  = $first;
            $this->second = $second;
        }
    }
    
    /**
     * チェーンの末尾に新しい Codec を連結させた, 新しい CodecChain インスタンスを返します.
     * 
     * @param  Peach_DF_Codec $c
     * @return Peach_DF_CodecChain
     */
    public function append(Peach_DF_Codec $c)
    {
        return new Peach_DF_CodecChain($this->first, new Peach_DF_CodecChain($this->second, $c));
    }
    
    /**
     * チェーンの先頭に新しい Codec を連結させた, 新しい CodecChain インスタンスを返します.
     * 
     * @param  Peach_DF_Codec $c
     * @return Peach_DF_CodecChain
     */
    public function prepend(Peach_DF_Codec $c)
    {
        return new Peach_DF_CodecChain($c, $this);
    }
    
    /**
     * このオブジェクトに指定された Codec を使って指定された値をデコードします.
     * 
     * @param  mixed $text デコード対象の値
     * @return mixed       変換結果
     */
    public function decode($text)
    {
        return $this->first->decode($this->second->decode($text));
    }
    
    /**
     * このオブジェクトに指定された Codec を使って指定された値をエンコードします.
     * 
     * @param  mixed $var エンコード対象の値
     * @return mixed      変換結果
     */
    public function encode($var)
    {
        return $this->second->encode($this->first->encode($var));
    }
}
