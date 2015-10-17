<?php
namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

class TextNode extends Node
{
    /**
     * @return DOMText
     */
    protected function getDomNode(){ return parent::getDomNode(); }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->sanitizeString($this->getDomNode()->nodeValue);
    }
}