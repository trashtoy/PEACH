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
     * このクラスが使用するグローバル Helper に紐付いている
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
     * 生成された要素をラップする HelperObject を返します.
     * 第 2 引数を指定することで, 生成された要素に属性を付与することが出来ます.
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
     * 引数にノードを指定した場合, そのノードの内容をコメントアウトします.
     * 
     * 第 2, 第 3 引数にコメントの接頭辞・接尾辞を含めることが出来ます.
     * 
     * @param  string|Peach_Markup_Component $contents コメントにしたいテキストまたはノード
     * @param  string $prefix コメントの接頭辞
     * @param  string $suffix コメントの接尾辞
     * @return Peach_Markup_HelperObject
     */
    public static function comment($contents = null, $prefix = "", $suffix = "")
    {
        $comment = new Peach_Markup_Comment($prefix, $suffix);
        return self::getHelper()->createObject($comment)->append($contents);
    }
    
    /**
     * IE 9 以前の Internet Explorer で採用されている条件付きコメントを生成します.
     * 以下にサンプルを挙げます.
     * <code>
     * echo Peach_Markup_Html::conditionalComment("lt IE 7", "He died on April 9, 2014.")->write();
     * </code>
     * このコードは次の文字列を出力します.
     * <code>
     * <!--[if lt IE 7]>He died on April 9, 2014.<![endif]-->
     * </code>
     * 第 2 引数を省略した場合は空の条件付きコメントを生成します.
     * 
     * @param  string                        $cond     条件文 ("lt IE 7" など)
     * @param  string|Peach_Markup_Component $contents 条件付きコメントで囲みたいテキストまたはノード
     * @return Peach_Markup_HelperObject 条件付きコメントを表現する HelperObject
     */
    public static function conditionalComment($cond, $contents = null)
    {
        return self::comment($contents, "[if {$cond}]>", "<![endif]");
    }
    
    /**
     * HTML の select 要素を生成します.
     * 第 1 引数にはデフォルトで選択されている値,
     * 第 2 引数には選択肢を配列で指定します.
     * キーがラベル, 値がそのラベルに割り当てられたデータとなります.
     * 
     * 引数を二次元配列にすることで, 一次元目のキーを optgroup にすることが出来ます.
     * 以下にサンプルを挙げます.
     * <code>
     * $candidates = array(
     *     "Fruit"   => array(
     *         "Apple"  => 1,
     *         "Orange" => 2,
     *         "Pear"   => 3,
     *         "Peach"  => 4,
     *     ),
     *     "Dessert" => array(
     *         "Chocolate" => 5,
     *         "Doughnut"  => 6,
     *         "Ice cream" => 7,
     *     ),
     *     "Others" => 8,
     * );
     * $select = Peach_Markup_Html::createSelectElement("6", $candidates, array("class" => "sample", "name" => "favorite"));
     * </code>
     * この要素を出力すると以下の結果が得られます.
     * <code>
     * <select class="sample" name="favorite">
     *     <optgroup label="Fruit">
     *         <option value="1">Apple</option>
     *         <option value="2">Orange</option>
     *         <option value="3">Pear</option>
     *         <option value="4">Peach</option>
     *     </optgroup>
     *     <optgroup label="Dessert">
     *         <option value="5">Chocolate</option>
     *         <option value="6" selected>Doughnut</option>
     *         <option value="7">Ice cream</option>
     *     </optgroup>
     *     <option value="8">Others</option>
     * </select>
     * </code>
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
                continue;
            }
            
            $option  = new Peach_Markup_ContainerElement("option");
            $option->setAttribute("value", $value);
            $value   = Peach_Util_Values::stringValue($value);
            if ($current === $value) {
                $option->setAttribute("selected");
            }
            $option->append($key);
            $result->append($option);
        }
        return $result;
    }
    
    /**
     * HTML の select 要素を生成し, 結果を HelperObject として返します.
     * 引数および処理内容は
     * {@link Peach_Markup_Html::createSelectElement() createSelectElement()}
     * と全く同じですが, 生成された要素を HelperObject でラップするところが異なります.
     * 
     * @see    Peach_Markup_Html::createSelectElement
     * @param  string $current    デフォルト値
     * @param  array  $candidates 選択肢の一覧
     * @param  array  $attr       追加で指定する属性 (class, id, style など)
     * @return Peach_Markup_HelperObject
     */
    public static function select($current, array $candidates, array $attr = array())
    {
        return self::tag(self::createSelectElement($current, $candidates, $attr));
    }
    
    /**
     * このクラスで定義されているメソッドを簡単に呼び出すためのエイリアスを定義します.
     * 引数の配列では, キーにメソッド名, 値にそのメソッドのエイリアス (関数名) を指定してください.
     * キーに使用することが出来る文字列 (メソッド名) は以下の通りです.
     * 
     * - {@link Peach_Markup_Html::tag() tag}
     * - {@link Peach_Markup_Html::comment() comment}
     * - {@link Peach_Markup_Html::conditionalComment() conditionalComment}
     * - {@link Peach_Markup_Html::select() select}
     * 
     * 使用例を挙げます.
     * <code>
     * Peach_Markup_Html::alias(array("tag" => "t", "comment" => "co"));
     * echo t("div")
     *     ->append(co("TEST"))
     *     ->append(t("h1")->append("Sample"))
     *     ->append(t("p")->append("Hello World!"))
     *     ->write();
     * </code>
     * このコードは以下の内容と等価です.
     * <code>
     * echo Peach_Markup_Html::tag("div")
     *     ->append(Peach_Markup_Html::comment("TEST"))
     *     ->append(Peach_Markup_Html::tag("h1")->append("Sample"))
     *     ->append(Peach_Markup_Html::tag("p")->append("Hello World!"))
     *     ->write();
     * </code>
     * いずれも以下の結果を出力します.
     * <code>
     * <div>
     *     <!--TEST-->
     *     <h1>Sample</h1>
     *     <p>Hello world!</p>
     * </div>
     * </code>
     * 
     * 引数を省略した場合は, array("tag" => "tag") として扱います.
     * 
     * @param  array $options 定義するエイリアスの一覧. キーがメソッド, 値が新しく定義する関数名
     * @throws InvalidArgumentException 不適切なクラスメソッド名を指定した /
     * 指定された関数名が既に存在していた / 関数名が不適切など
     */
    public static function alias(array $options = array())
    {
        static $validNames = array("tag", "comment", "conditionalComment", "select");
        if (!count($options)) {
            return self::alias(array("tag" => "tag"));
        }
        foreach ($options as $methodName => $funcName) {
            if (!in_array($methodName, $validNames)) {
                throw new InvalidArgumentException("Method '{$methodName}' is not found.");
            }
            if (function_exists($funcName)) {
                throw new InvalidArgumentException("Function '{$funcName}' is already defined.");
            }
            // http://www.php.net/manual/ja/functions.user-defined.php を参考
            if (!preg_match("/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/", $funcName)) {
                throw new InvalidArgumentException("Invalid function name: '{$funcName}'");
            }
        }
        
        if (isset($options["tag"])) {
            $tag = $options["tag"];
            eval("function {$tag}(\$n = null, \$a = array()) { return Peach_Markup_Html::tag(\$n, \$a); }");
        }
        if (isset($options["comment"])) {
            $comment = $options["comment"];
            eval("function {$comment}(\$c, \$p = '', \$s = '') { return Peach_Markup_Html::comment(\$c, \$p, \$s); }");
        }
        if (isset($options["conditionalComment"])) {
            $cond = $options["conditionalComment"];
            eval("function {$cond}(\$c1, \$c2) { return Peach_Markup_Html::conditionalComment(\$c1, \$c2); }");
        }
        if (isset($options["select"])) {
            $select = $options["select"];
            eval("function {$select}(\$c1, \$c2, \$a = array()) { return Peach_Markup_Html::select(\$c1, \$c2, \$a); }");
        }
    }
}
