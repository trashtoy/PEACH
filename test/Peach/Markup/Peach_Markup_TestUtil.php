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
}
