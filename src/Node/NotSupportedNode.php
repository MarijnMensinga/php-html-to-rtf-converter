<?php
namespace HtmlToRtf\Node;

use HtmlToRtf\Node;

class NotSupportedNode extends Node
{
    //do nothing
    public function parse(){ return ''; }
}