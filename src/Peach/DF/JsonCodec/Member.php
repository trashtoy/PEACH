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
 * JSON の BNF ルール member をあらわす Expression です.
 * RFC 7159 で定義されている以下のフォーマットを解釈します.
 * 
 * <pre>
 * member = string name-separator value
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_Member implements Peach_DF_JsonCodec_Expression
{
    /**
     *
     * @var string
     */
    private $key;
    
    /**
     *
     * @var mixed
     */
    private $value;
    
    public function __construct()
    {
        $this->key   = null;
        $this->value = null;
    }
    
    /**
     * 
     * @param Peach_DF_JsonCodec_Context $context
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        $string = new Peach_DF_JsonCodec_StringExpr();
        $string->handle($context);
        $this->key = $string->getResult();
        
        $nameSeparator = new Peach_DF_JsonCodec_StructuralChar(array(":"));
        $nameSeparator->handle($context);
        
        $value = new Peach_DF_JsonCodec_Value();
        $value->handle($context);
        $this->value = $value->getResult();
    }
    
    /**
     * 
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }
    
    /**
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
