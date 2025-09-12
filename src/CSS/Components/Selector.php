<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

/**
 * Class Selector
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class Selector implements Interfaces\Selector
{
    /**
     * @param string $selector
     */
    public function __construct(public string $selector)
    {
        //
    }

    /**
     * @param SelectorFormat $format
     * @return string
     */
    public function render(SelectorFormat $format): string
    {
        return $format->format($this->selector);
    }
}
