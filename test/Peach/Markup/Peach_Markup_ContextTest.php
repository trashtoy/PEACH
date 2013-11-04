<?php
abstract class Peach_Markup_ContextTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Peach_Markup_Context
     */
    protected $object;
    
    /**
     * @var Peach_Markup_Node
     */
    protected $testNode;
    
    
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }
    
    protected function getTestNode()
    {
        static $html = null;
        if ($html === null) {
            $html = new Peach_Markup_ContainerElement("html");
            $html->setAttribute("lang", "ja");
            $html->append($this->createHead());
            $html->append($this->createBody());
        }
        return $html;
    }
    
    private function createHead()
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
    
    private function createBody()
    {
        $body   = new Peach_Markup_ContainerElement("body");
        $body->append($this->createForm());
        return $body;
    }
    
    private function createForm()
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
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers Peach_Markup_Context::handle
     * @covers Peach_Markup_Context::getResult
     */
    public abstract function testGetResult();
}
