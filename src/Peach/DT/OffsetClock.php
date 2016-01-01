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
 * 指定された Clock を基準にして, 任意の秒数だけ未来または過去に移動させた現在日時を返す Clock の実装です.
 * 
 * @package DT
 */
class Peach_DT_OffsetClock extends Peach_DT_Clock
{
    /**
     * ベースとなる Clock です.
     * @var Peach_DT_Clock
     */
    private $base;
    
    /**
     * 移動させる秒数です. 負の値を指定した場合は過去, 正の値を指定した場合は未来の方向に移動します.
     * @var int
     */
    private $offset;
    
    /**
     * 現在時刻を指定された秒数だけ未来または過去にずらす OffsetClock オブジェクトを生成します.
     * 
     * @param int            $offset 秒数
     * @param Peach_DT_Clock $base   ベースとなる Clock オブジェクト. 未指定の場合は DefaultClock が適用される.
     */
    public function __construct($offset, Peach_DT_Clock $base = null)
    {
        if ($base === null) {
            $base = Peach_DT_DefaultClock::getInstance();
        }
        $this->offset = $offset;
        $this->base   = $base;
    }
    
    /**
     * ベースとなる Clock オブジェクトの getUnixTime() の結果を指定された秒数だけ加減した結果を返します.
     * 
     * @return int unix time
     */
    protected function getUnixTime()
    {
        return $this->base->getUnixTime() + $this->offset;
    }
}
