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
     * @param StyleSheet $styleSheet
     * @param string $selector
     * @return string
     */
    public function format(StyleSheet $styleSheet, string $selector): string;
}
