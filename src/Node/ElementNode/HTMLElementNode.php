<?php
namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

class HTMLElementNode extends ElementNode
{
    const DEFAULT_FONT = 'Calibri';
    const DEFAULT_FONT_SIZE = 12*2;

    public function parse()
    {
        //start rtf document
        $prepend = '{\rtf1\ansi\ansicpg1252';
        //set default fonts
        $prepend .= '\deff0{\fonttbl{\f0 '.self::DEFAULT_FONT.';}}';
        //set hyperlink color
        $prepend .= '{\colortbl ;\red0\green0\blue0 ;\red0\green0\blue255 ;}';
        //set default style
        $prepend .= '\f0\cf1\fs'.self::DEFAULT_FONT_SIZE.' ';

        $append = '}';
        $this->setRtfPrepend($prepend);
        $this->setRtfAppend($append);
        return parent::parse();
    }
}