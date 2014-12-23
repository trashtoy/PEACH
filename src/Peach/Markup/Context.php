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
 * 各ノードを変換する処理を担当するクラスです.
 * このクラスは Visitor パターンにより設計されています (Visitor クラスに相当します).
 * {@link Peach_Markup_Builder Builder} クラスと連携して以下のように動作します.
 * 
 * 1. エンドユーザーが Builder オブジェクトの {@link Peach_Markup_Builder::build() build()} メソッドを実行します
 * 2. build() メソッドの内部で新しい Context オブジェクトが生成されます
 * 3. Context オブジェクトの {@link Peach_Markup_Context::handle() handle()} メソッドが呼び出され, build() の引数に指定されたノードを変換します
 * 4. 変換結果を {@link Peach_Markup_Context::getResult() getResult()} から取り出し, build() メソッドの返り値として返します
 * 
 * @package Markup
 */
abstract class Peach_Markup_Context
{
    /**
     * 指定されたオブジェクトを処理します.
     * オブジェクトの種類に応じて, このクラスの具象クラスで定義された各 handle メソッドに処理が割り当てられます.
     * Visitor パターンの visit メソッドに相当します.
     * 
     * @param  Peach_Markup_Component $c 処理対象の Component
     */
    public final function handle(Peach_Markup_Component $c)
    {
        $c->accept($this);
    }
    
    /**
     * コンテナ要素を処理します.
     * @param  Peach_Markup_ContainerElement $node 処理対象のコンテナ要素
     */
    public abstract function handleContainerElement(Peach_Markup_ContainerElement $node);
    
    /**
     * 空要素タグを処理します.
     * @param  Peach_Markup_EmptyElement $node 処理対象の空要素
     */
    public abstract function handleEmptyElement(Peach_Markup_EmptyElement $node);
    
    /**
     * テキストノードを処理します.
     * @param  Peach_Markup_Text $node 処理対象のテキスト
     */
    public abstract function handleText(Peach_Markup_Text $node);
    
    /**
     * 整形済テキストを処理します.
     * @param  Peach_Markup_Code $node 処理対象の整形済テキスト
     */
    public abstract function handleCode(Peach_Markup_Code $node);
    
    /**
     * コメントノードを処理します.
     * @param  Peach_Markup_Comment $node 処理対象のコメント
     */
    public abstract function handleComment(Peach_Markup_Comment $node);
    
    /**
     * NodeList を処理します.
     * @param Peach_Markup_NodeList $node 処理対象の NodeList
     */
    public abstract function handleNodeList(Peach_Markup_NodeList $node);
    
    /**
     * None を処理します.
     * @param Peach_Markup_None $none 処理対象の None オブジェクト
     */
    public abstract function handleNone(Peach_Markup_None $none);
    
    /**
     * 処理結果を取得します. まだ handle() が実行されていない場合は NULL を返します.
     * 
     * @return mixed 処理結果
     */
    public abstract function getResult();
}
