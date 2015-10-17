<?php
namespace HtmlToRtf;

use HtmlToRtf\Node\TextNode;
use HtmlToRtf\Node\NotSupportedNode;
use HtmlToRtf\Node\ElementNode;
use HtmlToRtf\Node\ElementNode\HTMLElementNode;
use HtmlToRtf\Node\ElementNode\PElementNode;
use HtmlToRtf\Node\ElementNode\AElementNode;
use HtmlToRtf\Node\ElementNode\SpanElementNode;
use HtmlToRtf\Node\ElementNode\ListElementNode\ULElementNode;
use HtmlToRtf\Node\ElementNode\ListElementNode\OLElementNode;
use HtmlToRtf\Node\ElementNode\ListElementNode\LIElementNode;

use HtmlToRtf\Utils\UnicodeUtils;

class Node
{
    private static $_nodeStore = [];

    private $_domNode;

    /**
     * @param DOMNode $node
     */
    public function __construct($aDomNode)
    {
        $this->_domNode = $aDomNode;
    }

    /**
     * @param DOMNode $node
     * @return Node
     */
    public static function getInstance($node)
    {
        if(!isset($node)){
            return null;
        }
        $instanceId = spl_object_hash($node);
        if(!isset(self::$_nodeStore[$instanceId])){
            $nodeSpecific = null;
            switch($node->nodeType){
                //HTML element node
                case XML_ELEMENT_NODE:
                    /**
                     * @var DOMElement $node
                     */
                    switch($node->tagName){
                        case 'html':
                            $nodeSpecific = new HTMLElementNode($node);
                            break;
                        case 'body':
                            $nodeSpecific = new Node($node);
                            break;
                        case 'b':
                        case 'strong':
                            $nodeSpecific = new ElementNode($node, '{\b ', '}');
                            break;
                        case 'i':
                        case 'em':
                            $nodeSpecific = new ElementNode($node, '{\i ', '}');
                            break;
                        case 'u':
                            $nodeSpecific = new ElementNode($node, '{\ul ', '}');
                            break;
                        case 'a':
                            $nodeSpecific = new AElementNode($node);
                            break;
                        case 'ol':
                            $nodeSpecific = new OLElementNode($node);
                            break;
                        case 'ul':
                            $nodeSpecific = new ULElementNode($node);
                            break;
                        case 'li':
                            $nodeSpecific = new LIElementNode($node);
                            break;
                        case 'br':
                            $nodeSpecific = new ElementNode($node, '\line ');
                            break;
                        case 'p':
                            $nodeSpecific = new PElementNode($node);
                            break;
                        case 'span':
                            $nodeSpecific = new SpanElementNode($node);
                            break;

                        //TODO: html special chars (&mbsp; => \~)

                        default:
                            $nodeSpecific = new NotSupportedNode($node);
                    }
                    break;

                //Plaintext nodes
                case XML_TEXT_NODE:
                    $nodeSpecific = new TextNode($node);
                    break;

                //start document type nodes
                case XML_HTML_DOCUMENT_NODE:
                case XML_DOCUMENT_TYPE_NODE:
                    $nodeSpecific = new Node($node);
                    break;

                //remove non supported nodes
                default:
                    $nodeSpecific = new NotSupportedNode($node);
                    break;
            }
            self::$_nodeStore[$instanceId] = $nodeSpecific;
        }
        return self::$_nodeStore[$instanceId];
    }

    /**
     * @return string
     */
    protected function parseNodeChildren()
    {
        $rtf = '';
        foreach($this->getDomNode()->childNodes ?: [] as $childNode){
            $node = self::getInstance($childNode);
            $rtf .= $node->parse();
        }
        return $rtf;
    }

    /**
     * @return string
     */
    public function parse()
    {
        return $this->parseNodeChildren();
    }

    /**
     * Convert all unicode chars to \uXXXX?
     * @param String $string unicode string
     * @return string
     */
    protected function sanitizeString($string)
    {
        $stringNew = '';
        $stringArray = UnicodeUtils::strSplit($string);
        foreach($stringArray as $char){
            $code = ord($char);
            if($code < 128){
                $chr = chr($code);
            }else{
                $chr = '\u' . str_pad(UnicodeUtils::charToUnicodeOrd($char), 4, '0', STR_PAD_LEFT) . '?';
            }
            $stringNew .= $chr;
        }

        return $stringNew;
    }

    /**
     * @return array
     */
    protected function getNodePath()
    {
        return preg_split('/\//', $this->getDomNode()->getNodePath());
    }

    /**
     * @return DOMNode
     */
    protected function getDomNode()
    {
        return $this->_domNode;
    }

    public function getParent()
    {
        return Node::getInstance($this->getDomNode()->parentNode);
    }
}