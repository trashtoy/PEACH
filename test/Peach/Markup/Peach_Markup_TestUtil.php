<?php
class Peach_Markup_TestUtil
{
    private function __construct() {}
    
    /**
     * @return Peach_Markup_ContainerElement
     */
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
    
    /**
     * @return Peach_Markup_ContainerElement
     */
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
    
    /**
     * @return Peach_Markup_ContainerElement
     */
    private static function createBody()
    {
        $body   = new Peach_Markup_ContainerElement("body");
        $body->append(self::createForm());
        return $body;
    }
    
    /**
     * @return Peach_Markup_ContainerElement
     */
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
     * @param  Peach_Markup_Helper $h
     * @return Peach_Markup_HelperObject
     */
    public static function createTestHelperObject(Peach_Markup_Helper $h)
    {
        return $h->createObject("html")
            ->attr("lang", "ja")
            ->append($h->createObject("head")
                ->append($h->createObject("meta")->attr(array("http-equiv" => "Content-Type", "content" => "text/html; charset=UTF-8")))
                ->append($h->createObject("title")->append("TEST PAGE"))
            )
            ->append($h->createObject("body")
                ->append($h->createObject("form")
                    ->attr(array("method" => "post", "action" => "sample.php"))
                    ->append("Name")
                    ->append($h->createObject("input")->attr(array("type" => "text", "name" => "param1", "value" => "")))
                    ->append($h->createObject("br"))
                    ->append($h->createObject("input")
                        ->attr(array("type" => "checkbox", "name" => "flag1", "value" => "1"))
                        ->attr("checked")
                    )
                    ->append("Enable something")
                    ->append($h->createObject("br"))
                    ->append($h->createObject("input")->attr(array("type" => "submit", "name" => "submit", "value" => "Send")))
                )
            );
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
    
    /**
     * テストノードを DebugBuilder::build() で処理した場合の想定結果を返します.
     * @return string
     */
    public static function getDebugBuildResult()
    {
        static $expected = null;
        if ($expected === null) {
            $expected = implode("\r\n", array(
                "ContainerElement(html) {",
                "    ContainerElement(head) {",
                "        EmptyElement(meta)",
                "        ContainerElement(title) {",
                "            Text",
                "        }",
                "    }",
                "    ContainerElement(body) {",
                "        ContainerElement(form) {",
                "            Text",
                "            EmptyElement(input)",
                "            EmptyElement(br)",
                "            EmptyElement(input)",
                "            Text",
                "            EmptyElement(br)",
                "            EmptyElement(input)",
                "        }",
                "    }",
                "}",
            )) . "\r\n";
        }
        return $expected;
    }
}
