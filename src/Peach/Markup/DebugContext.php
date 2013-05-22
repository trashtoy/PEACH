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
class Peach_Markup_DebugContext extends Peach_Markup_Context
{
    /**
     *
     * @var Peach_Markup_Indent
     */
    private $indent;
    
    /**
     *
     * @var string
     */
    private $result;
    
    private $echoMode;
    
    /**
     * 
     */
    public function __construct()
    {
        $this->indent   = new Peach_Markup_Indent();
        $this->result   = "";
        $this->echoMode = true;
    }
    
    /**
     * 
     * @param string $name
     */
    private function startNode($name)
    {
        $result = $this->indent->indent() . $name . " {" . $this->indent->stepUp();
        if ($this->echoMode) {
            echo $result;
        }
        $this->result .= $result;
    }
    
    /**
     * 
     */
    private function endNode()
    {
        $result = $this->indent->stepDown() . "}" . $this->indent->breakCode();
        if ($this->echoMode) {
            echo $result;
        }
        $this->result .= $result;
    }
    
    private function append($contents)
    {
        $result = $this->indent->indent() . $contents . $this->indent->breakCode();
        if ($this->echoMode) {
            echo $result;
        }
        $this->result .= $result;
    }
    
    private function handleContainer(Peach_Markup_Container $container)
    {
        foreach ($container->getChildNodes() as $node) {
            $this->handle($node);
        }
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
    public function handleCdataSection(Peach_Markup_CdataSection $node)
    {
        $this->startNode("CDATA");
        $this->endNode();
    }

    public function handleComment(Peach_Markup_Comment $node)
    {
        $this->startNode("Comment");
        $this->handleContainer($node);
        $this->endNode();
    }

    public function handleContainerElement(Peach_Markup_ContainerElement $node)
    {
        $name = $node->getName();
        $this->startNode("ContainerElement({$name})");
        $this->handleContainer($node);
        $this->endNode();
    }

    public function handleEmptyElement(Peach_Markup_EmptyElement $node)
    {
        $name = $node->getName();
        $this->append("EmptyElement({$name})");
    }

    public function handleNodeList(Peach_Markup_NodeList $node)
    {
        $this->startNode("NodeList");
        $this->handleContainer($node);
        $this->endNode();
    }

    public function handleText(Peach_Markup_Text $node)
    {
        $this->append("Text");
    }

    public function handleCode(Peach_Markup_Code $node)
    {
        $this->append("Code");
    }
}
?>