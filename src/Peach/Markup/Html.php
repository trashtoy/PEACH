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
 * HTML �̏o�͂ɓ����������[�e�B���e�B�N���X�ł�.
 * 
 * @package Markup
 */
class Peach_Markup_Html
{
    /**
     *
     * @var Peach_Markup_Helper
     */
    private static $HELPER;
    
    /** ���̃N���X�̓C���X�^���X���ł��܂���. */
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
    
    /**
     * ���̃N���X�Ŏg�p���� Helper �C���X�^���X�����������܂�.
     * HTML �ł͂Ȃ� XHTML �`���Ń^�O�o�͂������ꍇ��, ������ true ���w�肵�Ă�������.
     * 
     * @param bool $isXHTML XHTML �`���ŏo�͂���ꍇ�� true
     */
    public static function init($isXHTML = false)
    {
        self::$HELPER = self::createHelper($isXHTML);
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
    
    /**
     * 
     * @param  string $name �v�f��
     * @param  array  $attr �ǉ��Ŏw�肷�鑮��
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
     * HTML �� select �v�f�𐶐����܂�.
     * �� 1 �����ɂ̓f�t�H���g�őI������Ă���l,
     * �� 2 �����ɂ͑I������z��Ŏw�肵�܂�.
     * �L�[�����x��, �l�����̃��x���Ɋ��蓖�Ă�ꂽ�f�[�^�ƂȂ�܂�.
     * ������񎟌��z��ɂ��邱�Ƃ�, �ꎟ���ڂ̃L�[�� optgroup �ɂ��邱�Ƃ��o���܂�.
     * 
     * @param  string $current    �f�t�H���g�l
     * @param  array  $candidates �I�����̈ꗗ
     * @param  array  $attr       �ǉ��Ŏw�肷�鑮�� (class, id, style �Ȃ�)
     * @return Peach_Markup_ContainerElement HTML �� select �v�f
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
     * select �v�f�Ɋ܂܂�� option �̈ꗗ���쐬���܂�.
     * 
     * @param  string $current    �f�t�H���g�l
     * @param  array  $candidates �I�����̈ꗗ
     * @return Peach_Markup_NodeList option �v�f�̈ꗗ
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
     * select �v�f�����b�v���� HelperObject ��Ԃ��܂�.
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
