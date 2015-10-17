<?php
namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

class ListElementNode extends ElementNode
{
    public function parse()
    {
        $prepend = '\pard';
        if($this->getParent() instanceof LIElementNode && $this->getDomNode()->parentNode->firstChild !== $this->getDomNode())
        {
            $prepend = '\par' . $prepend;
        }
        $this->setRtfPrepend($prepend);
        return parent::parse();
    }
}