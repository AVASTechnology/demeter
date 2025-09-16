<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Declaration;

/**
 * Interface DeclarationBlockFormatInterface
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
interface DeclarationBlock
{
    /**
     * @param StyleSheet $styleSheet
     * @param Declaration[] $declarations
     * @param int $nestLevel
     * @return string
     */
    public function format(StyleSheet $styleSheet, array $declarations, int $nestLevel = 0): string;
}
