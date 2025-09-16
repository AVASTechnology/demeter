<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock as DeclarationBlockInterface;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector as SelectorInterface;

/**
 * Interface RuleSet
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface RuleSet extends Statement
{
    /**
     * @param StyleSheet $styleSheet
     * @param SelectorInterface[] $selectors
     * @param DeclarationBlockInterface $declarationBlock
     * @param int $nestLevel
     * @return string
     */
    public function format(StyleSheet $styleSheet, array $selectors, DeclarationBlockInterface $declarationBlock, int $nestLevel = 0): string;
}
