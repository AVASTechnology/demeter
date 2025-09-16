<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Interface AtRule
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface AtRule extends Statement
{
    /**
     * @param StyleSheet $styleSheet
     * @param int $nestLevel
     * @return string
     */
    public function render(StyleSheet $styleSheet, int $nestLevel = 0): string;
}
