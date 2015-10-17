<?php
namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

class PElementNode extends ElementNode
{
    public function parse()
    {
        $prepend = '{\pard\sa200\sl276\slmult1';
        $append = '\par}';

        $css = $this->getAttribute('style');
        $css = strtolower($css);

        $styles = preg_split('/;/',$css);
        $styles = array_map('trim',$styles);
        foreach($styles as $styleDef)
        {
            if(empty($styleDef)){ continue; }

            $style = preg_split('/:/',$styleDef);
            $style = array_map('trim',$style);
            switch($style[0])
            {
                case 'text-align':
                    switch($style[1])
                    {
                        case 'left':
                            $prepend = $prepend . '\ql';
                            break;

                        case 'right':
                            $prepend = $prepend . '\qr';
                            break;

                        case 'justify':
                            $prepend = $prepend . '\qj';
                            break;

                        case 'center':
                            $prepend = $prepend . '\qc';
                            break;
                    }
                    break;
            }
        }
        $this->setRtfPrepend($prepend.' ');
        $this->setRtfAppend($append);

        return parent::parse();
    }
}