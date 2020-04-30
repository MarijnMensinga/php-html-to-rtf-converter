<?php
namespace HtmlToRtf;

use DOMDocument;
use HtmlToRtf\Node;

// References:
//  - https://www.safaribooksonline.com/library/view/RTF+Pocket+Guide/9781449302047/ch01.html
//  - http://www.pindari.com/rtf1.html
//  - http://www.biblioscape.com/rtf15_spec.htm

class HtmlToRtf
{
    const ESCAPE_CHARS = [
        '/\\\/' => '\\\\\\',
        '/\{/' => '\{',
        '/\}/' => '\}'
    ];

    /**
     * @var DOMDocument
     */
    private $_doc;

    public function __construct($html)
    {
        $this->_doc = new DOMDocument();
        $this->setHtml($html);
    }

    public function getRTF()
    {
        $node = Node::getInstance($this->getDoc());
        return $node->parse();
    }

    protected function getDoc()
    {
        return $this->_doc;
    }

    public function getRTFFile()
    {
        $rtf = $this->getRTF();
        header("Content-type: application/rtf");
        header('Content-Disposition: attachment; filename=test.rtf');
        echo $rtf;
        exit();
    }

    public function setHtml($html)
    {
        //http://stackoverflow.com/questions/11309194/php-domdocument-failing-to-handle-utf-8-characters
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');

        //escape reserved chars
        $html = preg_replace(array_keys(self::ESCAPE_CHARS), array_values(self::ESCAPE_CHARS), $html);

        $this->getDoc()->loadHTML($html);
        $this->removeEmptyTextNodes($this->getDoc());
    }

    private function removeEmptyTextNodes($DOMNode)
    {
        if(!($DOMNode instanceof DOMNode)) {
            return;
        }

        if($DOMNode instanceof DOMText && trim($DOMNode->nodeValue) === '') {
            $this->removeEmptyTextNodes($DOMNode->nextSibling);
            $DOMNode->parentNode->removeChild($DOMNode);
        } else {
            $this->removeEmptyTextNodes($DOMNode->nextSibling);
            $this->removeEmptyTextNodes($DOMNode->firstChild);
        }
    }
}




spl_autoload_register(function($class)
{
    $parts = explode('\\',$class);
    $found = ($parts[0] === 'HtmlToRtf');
    if($found)
    {
        array_shift($parts);
        $fileName = __DIR__.'/'.implode('/',$parts).'.php';
        $found = file_exists($fileName);
        if($found)
        {
            include $fileName;
        }
    }
    return $found;
});
