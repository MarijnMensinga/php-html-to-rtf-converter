<?php
namespace HtmlToRtf\Node\ElementNode;

use HtmlToRtf\Node\ElementNode;

class SpanElementNode extends ElementNode
{
    public function parse()
    {
        $prepend = '';
        $append = '';

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
                case 'text-decoration':
                    switch($style[1])
                    {
                        case 'line-through':
                            $prepend = $prepend . '{\strike';
                            $append = '}' . $append;
                            break;

                        case 'underline':
                            $prepend = $prepend . '{\ul';
                            $append = '}' . $append;
                            break;
                    }
                    break;
            }
        }
        $this->setRtfPrepend(count($prepend) > 0 ? $prepend.' ' : $prepend);
        $this->setRtfAppend($append);

        return parent::parse();
    }
}