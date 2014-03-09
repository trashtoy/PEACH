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
 * ノードの一覧をあらわすクラスです.
 * 
 * このクラスは Component を実装しているため,
 * {@link Peach_Markup_Context::handle()} の引数に渡すことが出来ます.
 * (実際の処理は {@link Peach_Markup_Context::handleNodeList()} で行われます)
 * 
 * ある要素に対して NodeList を追加した場合, このオブジェクト自体ではなく,
 * このオブジェクトに含まれる各ノードが追加されます.
 * 
 * 例えるならば DOM の NodeList と NodeFragment を兼任するクラスです.
 * 
 * @package Markup
 */
class Peach_Markup_NodeList implements Peach_Markup_Container
{
    /**
     * Peach_Markup_Node の配列です.
     * @var array
     */
    private $nodeList;
    
    /**
     * この NodeList を持つノードです. 存在しない場合は null となります.
     * @var Peach_Markup_Node
     */
    private $owner;
    
    /**
     * 新しい NodeList を生成します.
     * 引数に値を設定した場合, その値をリストに追加した状態で初期化します.
     * 
     * @param Peach_Markup_Component|array|string $var 追加するノード
     */
    public function __construct($var = null, Peach_Markup_Node $owner = null)
    {
        $this->nodeList = array();
        $this->owner    = $owner;
        $this->append($var);
    }
    
    /**
     * この NodeList に実際に追加される値を返します.
     * 
     * @param  mixed $var
     * @return Peach_Markup_Node|array 追加されるノード (またはノードの配列)
     */
    private function getAppendee($var)
    {
        if ($var instanceof Peach_Markup_None) {
            return null;
        }
        if ($var instanceof Peach_Markup_Node) {
            return $var;
        }
        if ($var instanceof Peach_Markup_Container) {
            return $var->getChildNodes();
        }
        if (is_array($var)) {
            $result = array();
            foreach ($var as $i) {
                $appendee = $this->getAppendee($i);
                if (is_array($appendee)) {
                    $result = array_merge($result, $appendee);
                } else {
                    $result[] = $appendee;
                }
            }
            return $result;
        }
        if (!isset($var)) {
            return null;
        }
        
        return new Peach_Markup_Text(Peach_Util_Values::stringValue($var));
    }
    
    /**
     * この NodeList の末尾に引数の値を追加します.
     * 
     * 引数がノードの場合は, 引数をそのまま NodeList の末尾に追加します.
     * 
     * 引数が Container の場合は, その Container に含まれる各ノードを追加します.
     * (Container 自身は追加されません)
     * 
     * 引数が配列の場合は, 配列に含まれる各ノードをこの NodeList に追加します.
     * 
     * 引数がノードではない場合は, 引数の文字列表現をテキストノードとして追加します.
     * 
     * @param Peach_Markup_Node|Peach_Markup_Container|array|string $var
     */
    public function append($var)
    {
        $appendee = $this->getAppendee($var);
        if ($appendee === null) {
            return;
        }
        
        if (isset($this->owner)) {
            $this->checkOwner($appendee);
        }
        if (is_array($appendee)) {
            $this->nodeList = array_merge($this->nodeList, $appendee);
        } else {
            $this->nodeList[] = $appendee;
        }
    }
    
    /**
     * 指定された Context にこのノードを処理させます.
     * {@link Peach_Markup_Context::handleNodeList()} を呼び出します.
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $context->handleNodeList($this);
    }
    
    /**
     * この NodeList に子ノードを追加する際に,
     * 親子関係が無限ループしないかどうか検査します.
     * 引数がこの NodeList のオーナーだった場合に InvalidArgumentException をスローします.
     * 引数が配列もしくは {@link Peach_Markup_Container Container} だった場合は,
     * その子ノードの一覧について再帰的に検査します.
     * 
     * @param  mixed $var 検査対象
     * @throws InvalidArgumentException 検査対象にこの NodeList のオーナーが含まれていた場合
     */
    private function checkOwner($var)
    {
        if (is_array($var)) {
            foreach ($var as $i) {
                $this->checkOwner($i);
            }
            return;
        }
        
        if ($var instanceof Peach_Markup_Container) {
            $this->checkOwner($var->getChildNodes());
        }
        if ($var === $this->owner) {
            throw new InvalidArgumentException("Tree-loop detected.");
        }
    }
    
    /**
     * この NodeList に含まれるノードの個数を返します.
     * @return int ノードの個数
     */
    public function size()
    {
        return count($this->nodeList);
    }
    
    /**
     * この NodeList に含まれるノードの一覧を配列で返します.
     * @return array
     */
    public function getChildNodes()
    {
        return $this->nodeList;
    }
}
