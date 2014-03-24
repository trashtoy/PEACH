<?php
class Peach_Markup_TestUtil
{
    private function __construct() {}
    
    public static function getTestNode()
    {
        static $html = null;
        if ($html === null) {
            $html = new Peach_Markup_ContainerElement("html");
            $html->setAttribute("lang", "ja");
            $html->append(self::createHead());
            $html->append(self::createBody());
        }
        return $html;
    }
    
    private static function createHead()
    {
        $meta   = new Peach_Markup_EmptyElement("meta");
        $meta->setAttributes(array("http-equiv" => "Content-Type", "content" => "text/html; charset=UTF-8"));
        $title  = new Peach_Markup_ContainerElement("title");
        $title->append("TEST PAGE");
        
        $head   = new Peach_Markup_ContainerElement("head");
        $head->append($meta);
        $head->append($title);
        return $head;
    }
    
    private static function createBody()
    {
        $body   = new Peach_Markup_ContainerElement("body");
        $body->append(self::createForm());
        return $body;
    }
    
    private static function createForm()
    {
        $text   = new Peach_Markup_EmptyElement("input");
        $text->setAttributes(array("type" => "text", "name" => "param1", "value" => ""));
        $br     = new Peach_Markup_EmptyElement("br");
        $check  = new Peach_Markup_EmptyElement("input");
        $check->setAttributes(array("type" => "checkbox", "name" => "flag1", "value" => "1"));
        $check->setAttribute("checked");
        $submit = new Peach_Markup_EmptyElement("input");
        $submit->setAttributes(array("type" => "submit", "name" => "submit", "value" => "Send"));
        
        $form   = new Peach_Markup_ContainerElement("form");
        $form->setAttributes(array("method" => "post", "action" => "sample.php"));
        $form->append("Name");
        $form->append($text);
        $form->append($br);
        $form->append($check);
        $form->append("Enable something");
        $form->append($br);
        $form->append($submit);
        return $form;
    }
    
    /**
     * テストノードをデフォルトの条件でマークアップした場合の想定結果を返します.
     * デフォルトの条件は以下の通りです.
     * - 文書型: XHTML
     * - インデント: 4 スペース
     * - 改行コード: CRLF
     * 
     * @return string
     */
    public static function getDefaultBuildResult()
    {
        static $expected = null;
        if ($expected === null) {
            $expected = implode("\r\n", array(
                '<html lang="ja">',
                '    <head>',
                '        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />',
                '        <title>TEST PAGE</title>',
                '    </head>',
                '    <body>',
                '        <form method="post" action="sample.php">',
                '            Name',
                '            <input type="text" name="param1" value="" />',
                '            <br />',
                '            <input type="checkbox" name="flag1" value="1" checked="checked" />',
                '            Enable something',
                '            <br />',
                '            <input type="submit" name="submit" value="Send" />',
                '        </form>',
                '    </body>',
                '</html>',
            ));
        }
        return $expected;
    }
    
    /**
     * 条件をカスタマイズした状態における, テストノードのマークアップ結果を返します.
     * 条件は以下の通りです.
     * - 文書型: HTML
     * - インデント: 2 スペース
     * - 改行コード: LF
     * 
     * @return string
     */
    public static function getCustomBuildResult()
    {
        static $expected = null;
        if ($expected === null) {
            $expected = implode("\n", array(
                '<html lang="ja">',
                '  <head>',
                '    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">',
                '    <title>TEST PAGE</title>',
                '  </head>',
                '  <body>',
                '    <form method="post" action="sample.php">',
                '      Name',
                '      <input type="text" name="param1" value="">',
                '      <br>',
                '      <input type="checkbox" name="flag1" value="1" checked>',
                '      Enable something',
                '      <br>',
                '      <input type="submit" name="submit" value="Send">',
                '    </form>',
                '  </body>',
                '</html>',
            ));
        }
        return $expected;
    }
}
