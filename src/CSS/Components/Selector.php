<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

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
     * @param StyleSheet $styleSheet
     * @return string
     */
    public function render(StyleSheet $styleSheet): string
    {
        return $styleSheet->selectorFormat->format($styleSheet, $this->selector);
    }
}
