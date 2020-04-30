<?php
namespace HtmlToRtf\Node;

use DOMNode;
use HtmlToRtf\Node;

class TextNode extends Node
{
    /**
     * @return DOMNode
     */
    protected function getDomNode() {
        return parent::getDomNode();
    }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->sanitizeString($this->getDomNode()->nodeValue);
    }
}
