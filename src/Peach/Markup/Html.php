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
 * @package Markup
 */
class Peach_Markup_Html
{
    /**
     *
     * @var Peach_Markup_Helper
     */
    private static $HELPER;
    
    private function __construct() {}
    
    public static function getBuilder($xml = false)
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
     * 
     * @param  bool $xml
     * @return Peach_Markup_Helper
     */
    private static function createHelper($xml = false)
    {
        static $emptyNodeNames = null;
        if (!isset($emptyNodeNames)) {
            $emptyNodeNames = array("meta", "link", "img", "input", "br", "hr");
        }
        return new Peach_Markup_Helper(self::getBuilder($xml), $emptyNodeNames);
    }
    
    public static function init($xml = false)
    {
        self::$HELPER = self::createHelper($xml);
    }
    
    /**
     * 
     * @return Peach_Markup_Helper
     */
    public static function getHelper()
    {
        if (!isset(self::$HELPER)) {
            self::init();
        }
        return self::$HELPER;
    }
    
    public static function tag($name, array $attr = array())
    {
        return self::getHelper()->createObject($name, $attr);
    }
    
    public static function comment($contents, $prefix = "", $suffix = "")
    {
        $comment = new Peach_Markup_Comment($prefix, $suffix);
        return self::getHelper()->createObject($comment)->append($contents);
    }
    
    public static function conditionalComment($contents, $cond)
    {
        return self::comment($contents, "[{$cond}]>", "<![endif]");
    }
    
    public static function select($current, array $candidates, array $attr = array())
    {
        $select      = self::tag("select", $attr);
        $currentText = Peach_Util_Values::stringValue($current);
        return $select->append($this->createOptions($currentText, $candidates));
    }
    
    private static function createOptions($current, array $candidates)
    {
        $result = self::tag();
        foreach ($candidates as $key => $value) {
            if (is_array($value)) {
                $result->append(
                        tag("optgroup", array("label" => $key))
                        ->append(self::createOptions($current, $value))
                );
            } else {
                $optAttr = array("value" => $value);
                $value   = Peach_Util_Values::stringValue($value);
                if ($current === $value) {
                    $optAttr["selected"] = null;
                }
                $result->append(tag("option", $optAttr)->append($key));
            }
        }
        return $result;
    }
}
?>