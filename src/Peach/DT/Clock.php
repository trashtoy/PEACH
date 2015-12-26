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
 * 現在時刻を生成するためのクラスです. このクラスのインスタンスは
 * {@link Peach_DT_Date::now},
 * {@link Peach_DT_Datetime::now},
 * {@link Peach_DT_Timestamp::now}
 * などのメソッドの引数に渡す形で使用します.
 * 
 * このクラスの具象クラスは以下の用途で利用されることを想定しています.
 * 
 * - 現在時刻に依存する機能の単体テスト
 * - 現在時刻を過去または未来にセットして特定の機能をエミュレートする
 * 
 * @package DT
 */
abstract class Peach_DT_Clock
{
    /**
     * この Clock が指し示す時間を Unix time として返します.
     * 
     * @return int Unix time
     */
    abstract protected function getUnixTime();
    
    /**
     * {@link Peach_DT_Clock::getUnixTime()} で取得した値を使って
     * Timestamp インスタンスを生成します.
     * 
     * @return Peach_DT_Timestamp
     */
    public function getTimestamp()
    {
        $ms = $this->getUnixTime();
        return Peach_DT_UnixTimeFormat::getInstance()->parseTimestamp($ms);
    }
}
