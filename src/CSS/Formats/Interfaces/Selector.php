<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

/**
 * Interface Selector
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface Selector
{
    /**
     * @param string $selector
     * @return string
     */
    public function format(string $selector): string;
}
