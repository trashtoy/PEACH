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
 * JSON の BNF ルール value をあらわす Expression です.
 * RFC 7159 で定義されている以下のフォーマットを解釈します.
 * 
 * <pre>
 * object = begin-object [ member *( value-separator member ) ] end-object
 * </pre>
 * 
 * @package DF
 * @ignore
 */
class Peach_DF_JsonCodec_ObjectExpr implements Peach_DF_JsonCodec_Expression
{
    /**
     * @var mixed
     */
    private $result;
    
    public function __construct()
    {
        $this->result = null;
    }
    
    /**
     * 
     * Peach_DF_JsonCodec_Context $context
     */
    public function handle(Peach_DF_JsonCodec_Context $context)
    {
        $beginObject = new Peach_DF_JsonCodec_StructuralChar(array("{"));
        $beginObject->handle($context);
        
        $container = $this->getContainer($context);
        if ($context->current() === "}") {
            $endObject = new Peach_DF_JsonCodec_StructuralChar(array("}"));
            $endObject->handle($context);
            $this->result = $container->getResult();
            return;
        }
        
        while (true) {
            if ($context->current() === "}") {
                $context->throwException("Closing bracket after comma is not permitted");
            }
            $member = new Peach_DF_JsonCodec_Member();
            $member->handle($context);
            $container->setMember($member);
            
            $struct = new Peach_DF_JsonCodec_StructuralChar(array(",", "}"));
            $struct->handle($context);
            if ($struct->getResult() === "}") {
                $this->result = $container->getResult();
                break;
            }
        }
    }
    
    /**
     * 
     * @return mixed OBJECT_AS_ARRAY オプションが ON の場合は配列, OFF の場合は stdClass オブジェクト
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * 
     * @param  Peach_DF_JsonCodec_Context $context
     * @return Peach_DF_JsonCodec_ObjectExpr_Container
     */
    private function getContainer(Peach_DF_JsonCodec_Context $context)
    {
        $asArray = $context->getOption(Peach_DF_JsonCodec::OBJECT_AS_ARRAY);
        return $asArray ? new Peach_DF_JsonCodec_ObjectExpr_ArrayContainer() : new Peach_DF_JsonCodec_ObjectExpr_StdClassContainer();
    }
}

/**
 * @ignore
 */
interface Peach_DF_JsonCodec_ObjectExpr_Container
{
    /**
     * 
     * @return mixed 解析結果
     */
    public function getResult();

    /**
     * @param Peach_DF_JsonCodec_Member $member
     */
    public function setMember(Peach_DF_JsonCodec_Member $member);
}

/**
 * @ignore
 */
class Peach_DF_JsonCodec_ObjectExpr_ArrayContainer implements Peach_DF_JsonCodec_ObjectExpr_Container
{
    private $result;
    
    public function __construct()
    {
        $this->result = array();
    }
    
    /**
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * 
     * @param Peach_DF_JsonCodec_Member $member
     */
    public function setMember(Peach_DF_JsonCodec_Member $member)
    {
        $key = $member->getKey();
        $this->result[$key] = $member->getValue();
    }
}

/**
 * @ignore
 */
class Peach_DF_JsonCodec_ObjectExpr_StdClassContainer implements Peach_DF_JsonCodec_ObjectExpr_Container
{
    /**
     *
     * @var stdClass
     */
    private $result;
    
    public function __construct()
    {
        $this->result = new stdClass();
    }
    
    /**
     * 
     * @return stdClass
     */
    public function getResult()
    {
        return $this->result;
    }
    
    /**
     * 
     * @param Peach_DF_JsonCodec_Member $member
     */
    public function setMember(Peach_DF_JsonCodec_Member $member)
    {
        $key = $member->getKey();
        $this->result->$key = $member->getValue();
    }
}
