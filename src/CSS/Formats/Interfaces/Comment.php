<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

/**
 * Interface Comment
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface Comment extends Statement
{
    /**
     * @param StyleSheet $styleSheet
     * @param string $content
     * @param int $nestLevel
     * @return string
     */
    public function format(StyleSheet $styleSheet, string $content, int $nestLevel = 0): string;
}
