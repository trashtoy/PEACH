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
            $emptyNodeNames = array("meta", "link", "img", "input", "br", "hr");
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
     * 
     * @param  string $name 要素名
     * @param  array  $attr 追加で指定する属性
     * @return Peach_Markup_HelperObject
     */
    public static function tag($name, array $attr = array())
    {
        return self::getHelper()->createObject($name, $attr);
    }
    
    /**
     * 
     * @param  mixed  $contents
     * @param  string $prefix
     * @param  string $suffix
     * @return Peach_Markup_HelperObject
     */
    public static function comment($contents, $prefix = "", $suffix = "")
    {
        $comment = new Peach_Markup_Comment($prefix, $suffix);
        return self::getHelper()->createObject($comment)->append($contents);
    }
    
    /**
     * 
     * @param  mixed  $contents
     * @param  string $cond
     * @return Peach_Markup_HelperObject
     */
    public static function conditionalComment($contents, $cond)
    {
        return self::comment($contents, "[{$cond} ]>", "<![endif]");
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
