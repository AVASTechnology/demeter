<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Interface Selector
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface Selector
{
    /**
     * @var string $selector
     */
    public string $selector { get; set; }

    /**
     * @param StyleSheet $styleSheet
     * @return string
     */
    public function render(StyleSheet $styleSheet): string;
}
