<?php
/**
 * 各 Component の accept() のテストを行うための Context です.
 */
class Peach_Markup_TestContext extends Peach_Markup_Context
{
    /**
     * @var string
     */
    private $result;
    
    public function getResult()
    {
        return $this->result;
    }
    
    public function handleCode(Peach_Markup_Code $node)
    {
        $this->result = "handleCode";
    }
    
    public function handleComment(Peach_Markup_Comment $node)
    {
        $this->result = "handleComment";
    }
    
    public function handleContainerElement(Peach_Markup_ContainerElement $node)
    {
        $this->result = "handleContainerElement";
    }
    
    public function handleEmptyElement(Peach_Markup_EmptyElement $node)
    {
        $this->result = "handleEmptyElement";
    }
    
    public function handleNodeList(Peach_Markup_NodeList $node)
    {
        $this->result = "handleNodeList";
    }
    
    public function handleNone(Peach_Markup_None $none)
    {
        $this->result = "handleNone";
    }
    
    public function handleText(Peach_Markup_Text $node)
    {
        $this->result = "handleText";
    }
}
