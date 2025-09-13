<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

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
     * @param SelectorFormat $format
     * @return string
     */
    public function render(SelectorFormat $format): string;
}
