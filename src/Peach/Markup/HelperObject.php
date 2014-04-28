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
 * 既存の Component をラップして, ノードツリーの構築を簡略化・省力化するための糖衣構文を備えたクラスです.
 * 主に (MVC フレームワークで言うところの) View の範囲で使用されることを想定しています.
 * 
 * @package Markup
 */
class Peach_Markup_HelperObject implements Peach_Markup_Container
{
    /**
     *
     * @var Peach_Markup_Helper
     */
    private $helper;
    
    /**
     *
     * @var Peach_Markup_Component
     */
    private $node;
    
    /**
     * 指定された Helper オブジェクトに紐付けられた新しいインスタンスを構築します.
     * このコンストラクタは {@link Peach_Markup_Helper::createObject()} から呼び出されます.
     * 通常は, エンドユーザーがコンストラクタを直接呼び出す機会はありません.
     * 
     * @param Peach_Markup_Helper $helper
     * @param mixed $var このオブジェクトがラップする値 (テキスト, Component など)
     */
    public function __construct(Peach_Markup_Helper $helper, $var)
    {
        $this->helper = $helper;
        $this->node   = $helper->createNode($var);
    }
    
    /**
     * このオブジェクトがラップしているノードを返します.
     * @return Peach_Markup_Component
     */
    public function getNode()
    {
        return $this->node;
    }
    
    /**
     * このオブジェクトの子ノードとして, 指定された値を追加します.
     * このオブジェクトがラップしているオブジェクトが Container でない場合は何もしません.
     * 
     * @param  mixed $var                追加される値
     * @return Peach_Markup_HelperObject 自分自身
     */
    public function append($var)
    {
        $node = $this->node;
        if ($node instanceof Peach_Markup_Container) {
            $appendee = ($var instanceof Peach_Markup_HelperObject) ? $var->getNode() : $var;
            $node->append($appendee);
        }
        
        return $this;
    }
    
    /**
     * 指定された Container にこのオブジェクトを追加します.
     * 以下の 2 つのコードは, どちらも $obj2 の中に $obj1 を追加しています.
     * <code>
     * $obj1->appendTo($obj2);
     * $obj2->append($obj1);
     * </code>
     * {@link Peach_Markup_HelperObject::append() append()}
     * との違いは, 返り値が $obj1 になるか $obj2 になるかという点にあります.
     * 
     * @param  Peach_Markup_Container    $container 追加先の Container
     * @return Peach_Markup_HelperObject            自分自身
     */
    public function appendTo(Peach_Markup_Container $container)
    {
        $container->append($this->getNode());
        return $this;
    }
    
    /**
     * 指定された文字列を整形済コードとして追加します.
     * 
     * @param string|Peach_Markup_Code $code 追加対象の整形済文字列
     * @return Peach_Markup_HelperObject このオブジェクト自身
     */
    public function appendCode($code)
    {
        if (!($code instanceof Peach_Markup_Code)) {
            return $this->appendCode(new Peach_Markup_Code($code));
        }
        
        return $this->append($code);
    }
    
    /**
     * {@link Peach_Markup_Element::setAttribute() setAttribute()}
     * および
     * {@link Peach_Markup_Element::setAttributes() setAttributes()}
     * の糖衣構文です.
     * 引数が配列の場合は setAttributes() を実行し,
     * 引数が 1 つ以上の文字列の場合は setAttribute() を実行します.
     * もしもこのオブジェクトがラップしているノードが Element ではなかった場合,
     * このメソッドは何も行いません.
     * 
     * jQuery のようなメソッドチェインを実現するため, このオブジェクト自身を返します.
     * 
     * @param  string|array|Peach_Util_ArrayMap $var セットする属性
     * @return Peach_Markup_HelperObject このオブジェクト自身
     */
    public function attr()
    {
        $node  = $this->node;
        if (!($node instanceof Peach_Markup_Element)) {
            return $this;
        }
        
        $count = func_num_args();
        if (!$count) {
            return $this;
        }
        
        $args  = func_get_args();
        $first = $args[0];
        if (($first instanceof Peach_Util_ArrayMap) || is_array($first)) {
            $node->setAttributes($first);
        } else {
            $second = (1 < $count) ? $args[1] : null;
            $node->setAttribute($first, $second);
        }
        return $this;
    }
    
    /**
     * このオブジェクトの子ノード一覧をあらわす HelperObject を返します.
     * @return Peach_Markup_HelperObject
     */
    public function children()
    {
        if ($this->node instanceof Peach_Markup_NodeList) {
            return $this;
        }
        
        $result = $this->helper->createObject(null);
        if ($this->node instanceof Peach_Markup_Container) {
            $result->append($this->node->getChildNodes());
        }
        return $result;
    }
    
    /**
     * この HelperObject をレンダリングします.
     * 
     * @return mixed 出力結果. デフォルトではマークアップされた結果の文字列
     */
    public function write()
    {
        return $this->helper->write($this);
    }
    
    /**
     * この HelperObject をデバッグ出力します.
     * @return string
     */
    public function debug()
    {
        static $debug = null;
        if ($debug === null) {
           $debug = new Peach_Markup_DebugBuilder();
        }
        return $debug->build($this);
    }
    
    /**
     * この HelperObject がラップしている要素の属性をコピーして, 新しい要素を生成します.
     * もしもラップしているオブジェクトが Element ではなかった場合は
     * 空の NodeList をラップする HelperObject を返します.
     * 
     * @return Peach_Markup_HelperObject コピーされた要素をラップする HelperObject
     */
    public function prototype()
    {
        return $this->helper->createObject($this->createPrototype());
    }
    
    /**
     * @return Peach_Markup_Element
     */
    private function createPrototype()
    {
        $original = $this->node;
        if ($original instanceof Peach_Markup_ContainerElement) {
            $node = new Peach_Markup_ContainerElement($original->getName());
            $node->setAttributes($original->getAttributes());
            return $node;
        }
        if ($original instanceof Peach_Markup_EmptyElement) {
            $node = new Peach_Markup_EmptyElement($original->getName());
            $node->setAttributes($original->getAttributes());
            return $node;
        }
        
        return null;
    }
    
    /**
     * このオブジェクトがラップしているノードの accept() を呼び出します.
     * @param Peach_Markup_Context $context
     */
    public function accept(Peach_Markup_Context $context)
    {
        $this->node->accept($context);
    }
    
    /**
     * このオブジェクトの子ノードの一覧を取得します.
     * もしもこのオブジェクトがラップしているノードが {@link Peach_Markup_Container Container}
     * だった場合は, そのオブジェクトの子ノードの一覧を返します.
     * それ以外は空の配列を返します.
     * @return array
     */
    public function getChildNodes()
    {
        $node = $this->node;
        return ($node instanceof Peach_Markup_Container) ? $node->getChildNodes() : array();
    }
}
