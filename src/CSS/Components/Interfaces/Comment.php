<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Interface Comment
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface Comment extends Statement
{
    /**
     * @param StyleSheet $styleSheet
     * @param int $nestLevel
     * @return string
     */
    public function render(StyleSheet $styleSheet, int $nestLevel = 0): string;
}
