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
 * JSON の BNF ルール array をあらわす Expression です.
 * RFC 7159 で定義されている以下のフォーマットを解釈します.
 * 
 * <pre>
 * array = begin-array [ value *( value-separator value ) ] end-array
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_ArrayExpr implements Peach_DF_JsonCodec_Expression
{
    /**
     *
     * @var array
     */
    private $result;
    
    public function __construct()
    {
        $this->result = null;
    }
    
    /**
     * 
     * @param Peach_DF_JsonCodec_Context $context
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        $beginArray = new Peach_DF_JsonCodec_StructuralChar(array("["));
        $beginArray->handle($context);
        
        if ($context->current() === "]") {
            $endArray = new Peach_DF_JsonCodec_StructuralChar(array("]"));
            $endArray->handle($context);
            $this->result = array();
            return;
        }
        
        $result = array();
        while (true) {
            $value = new Peach_DF_JsonCodec_Value();
            $value->handle($context);
            $result[] = $value->getResult();
            
            $struct = new Peach_DF_JsonCodec_StructuralChar(array(",", "]"));
            $struct->handle($context);
            if ($struct->getResult() === "]") {
                $this->result = $result;
                break;
            }
        }
    }
    
    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
}
