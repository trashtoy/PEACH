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
 * HTML の出力に特化したユーティリティクラスです.
 * このクラスに紐付いているグローバルな {@link Peach_Markup_Helper Helper} オブジェクトを使って
 * HTML のマークアップを行います.
 * 
 * @package Markup
 */
class Peach_Markup_Html
{
    /**
     * HTML のマークアップに利用するグローバル Helper です.
     * @var Peach_Markup_Helper
     */
    private static $HELPER;
    
    /** このクラスはインスタンス化できません. */
    private function __construct() {}
    
    /**
     * @param  bool $xml
     * @return Peach_Markup_DefaultBuilder
     */
    private static function createBuilder($xml = false)
    {
        static $breakControl = null;
        if (!isset($breakControl)) {
            $breakControl = new Peach_Markup_NameBreakControl(
                array("html", "head", "body", "ul", "ol", "dl", "table"),
                array("pre", "code", "textarea")
            );
        }
        $renderer = $xml ? Peach_Markup_XmlRenderer::getInstance() : Peach_Markup_SgmlRenderer::getInstance();
        $builder  = new Peach_Markup_DefaultBuilder();
        $builder->setBreakControl($breakControl);
        $builder->setRenderer($renderer);
        return $builder;
    }
    
    /**
     * グローバル Helper を新規作成します.
     * @param  bool $xml
     * @return Peach_Markup_Helper
     */
    private static function createHelper($xml = false)
    {
        static $emptyNodeNames = null;
        if (!isset($emptyNodeNames)) {
            // HTML4.01 および HTML5 (2014-02-04 勧告候補時点) の空要素一覧です
            $emptyNodeNames = array("area", "base", "basefont", "br", "col", "command", "embed", "frame", "hr", "img", "input", "isindex", "keygen", "link", "meta", "param", "source", "track", "wbr");
        }
        return new Peach_Markup_Helper(self::createBuilder($xml), $emptyNodeNames);
    }
    
    /**
     * このクラスが使用するグローバル Helper を初期化します.
     * HTML ではなく XHTML 形式でタグ出力したい場合は, 引数に true を指定してください.
     * 
     * @param bool $isXHTML XHTML 形式で出力する場合は true
     */
    public static function init($isXHTML = false)
    {
        self::$HELPER = self::createHelper($isXHTML);
    }
    
    /**
     * このクラスが使用するグローバル Helper を返します.
     * もしも {@link Peach_Markup_Html::init() init()} が一度も実行されていない場合は,
     * HTML 形式のマークアップを行う Helper オブジェクトをグローバル Helper として設定します.
     * 
     * @return Peach_Markup_Helper このクラスが使用するグローバル Helper
     */
    public static function getHelper()
    {
        if (!isset(self::$HELPER)) {
            self::init();
        }
        return self::$HELPER;
    }
    
    /**
     * このクラスが利用するグローバル Helper インスタンスに紐付いている
     * {@link Peach_Markup_DefaultBuilder DefaultBuilder} オブジェクトを返します.
     * 返り値の Builder に対する変更は, グローバル Helper にも影響します.
     * @return Peach_Markup_DefaultBuilder 
     */
    public static function getBuilder()
    {
        return self::getHelper()->getBuilder();
    }
    
    /**
     * 指定された文字列を要素名とする要素を作成します.
     * 第 2 引数を指定することで, 生成された要素に属性を付与することが出来ます.
     * 生成された要素をラップする HelperObject を返します.
     * 
     * その他の使い方として, 以下のことが出来ます.
     * 
     * - 引数に null を指定したか, または引数を省略した場合, 空の NodeList をラップする HelperObject を返します.
     * - 引数に任意の {@link Peach_Markup_Node Node} を指定した場合, 引数のノードをラップする HelperObject を返します.
     * 
     * @param  string $name 要素名
     * @param  array  $attr 追加で指定する属性の一覧 (キーが属性名, 値が属性値)
     * @return Peach_Markup_HelperObject
     */
    public static function tag($name = null, array $attr = array())
    {
        return self::getHelper()->createObject($name, $attr);
    }
    
    /**
     * 指定された内容のコメントノードを作成します.
     * 引数に文字列を指定した場合, 引数の文字列のコメントを作成します.
     * 引数にノードを指定した場合, 引数のノードの出力内容をコメントアウトします.
     * 第 2, 第 3 引数にコメントの接頭辞・接尾辞を含めることが出来ます.
     * 
     * @param  string|Peach_Markup_Component $contents コメントにしたいテキストまたはノード
     * @param  string $prefix コメントの接頭辞
     * @param  string $suffix コメントの接尾辞
     * @return Peach_Markup_HelperObject
     */
    public static function comment($contents, $prefix = "", $suffix = "")
    {
        $comment = new Peach_Markup_Comment($prefix, $suffix);
        return self::getHelper()->createObject($comment)->append($contents);
    }
    
    /**
     * IE 9 以前の Internet Explorer で採用されている条件付きコメントを生成します.
     * 使用例は以下の通りです.
     * <code>
     * echo Peach_Markup_Html::conditionalComment("He died on April 9, 2014.", "lt IE 7")->write();
     * </code>
     * このコードは次の文字列を出力します.
     * <code>
     * <!--[if lt IE 7]>He died on April 9, 2014.<![endif]-->
     * </code>
     * 
     * @param  string|Peach_Markup_Component $contents 条件付きコメントで囲みたいテキストまたはノード
     * @param  string                        $cond     条件付きコメントの内容
     * @return Peach_Markup_HelperObject
     */
    public static function conditionalComment($contents, $cond)
    {
        return self::comment($contents, "[if {$cond}]>", "<![endif]");
    }
    
    /**
     * HTML の select 要素を生成します.
     * 第 1 引数にはデフォルトで選択されている値,
     * 第 2 引数には選択肢を配列で指定します.
     * キーがラベル, 値がそのラベルに割り当てられたデータとなります.
     * 引数を二次元配列にすることで, 一次元目のキーを optgroup にすることが出来ます.
     * 
     * @param  string $current    デフォルト値
     * @param  array  $candidates 選択肢の一覧
     * @param  array  $attr       追加で指定する属性 (class, id, style など)
     * @return Peach_Markup_ContainerElement HTML の select 要素
     */
    public static function createSelectElement($current, array $candidates, array $attr = array())
    {
        $currentText = Peach_Util_Values::stringValue($current);
        $select      = new Peach_Markup_ContainerElement("select");
        $select->setAttributes($attr);
        $select->append(self::createOptions($currentText, $candidates));
        return $select;
    } 
   
    /**
     * select 要素に含まれる option の一覧を作成します.
     * 
     * @param  string $current    デフォルト値
     * @param  array  $candidates 選択肢の一覧
     * @return Peach_Markup_NodeList option 要素の一覧
     */
    private static function createOptions($current, array $candidates)
    {
        $result = new Peach_Markup_NodeList();
        foreach ($candidates as $key => $value) {
            if (is_array($value)) {
                $optgroup = new Peach_Markup_ContainerElement("optgroup");
                $optgroup->setAttribute("label", $key);
                $optgroup->append(self::createOptions($current, $value));
                $result->append($optgroup);
            } else {
                $option  = new Peach_Markup_ContainerElement("option");
                $option->setAttribute("value", $value);
                $value   = Peach_Util_Values::stringValue($value);
                if ($current === $value) {
                    $option->setAttribute("selected");
                }
                $option->append($key);
                $result->append($option);
            }
        }
        return $result;
    }
    
    /**
     * select 要素をラップした HelperObject を返します.
     * 
     * @see    Peach_Markup_Html::createSelectElement
     * @param  string $current
     * @param  array  $candidates
     * @param  array  $attr
     * @return Peach_Markup_HelperObject
     */
    public static function select($current, array $candidates, array $attr = array())
    {
        return self::tag(self::createSelectElement($current, $candidates, $attr));
    }
}
