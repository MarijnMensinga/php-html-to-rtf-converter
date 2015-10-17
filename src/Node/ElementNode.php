<?php
namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

class ElementNode extends Node
{
    private $_rtfPrepend;
    private $_rtfAppend;
    private $_attributes = null;

    public function __construct($node, $rtfPrepend = '', $rtfAppend = '')
    {
        parent::__construct($node);
        $this->setRtfPrepend($rtfPrepend);
        $this->setRtfAppend($rtfAppend);
    }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->getRtfPrepend().$this->parseNodeChildren().$this->getRtfAppend();
    }

    public function getAttributes()
    {
        if($this->_attributes === null)
        {
            foreach($this->getDomNode()->attributes as $attribute)
            {
                $this->_attributes[$attribute->name] = $attribute->value;
            }
        }
        return $this->_attributes;
    }

    public function getAttribute($name){ return $this->getAttributes()[$name]; }
    protected function setRtfPrepend($rtf){ $this->_rtfPrepend = $rtf; }
    protected function setRtfAppend($rtf){ $this->_rtfAppend = $rtf; }
    /**
     * @return string
     */
    protected function getRtfPrepend(){ return $this->_rtfPrepend; }
    /**
     * @return string
     */
    protected function getRtfAppend(){ return $this->_rtfAppend; }
}