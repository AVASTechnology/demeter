<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock as DeclarationBlock;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector as Selector;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class RuleSet
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class RuleSet implements Interfaces\RuleSet
{
    /**
     * @param Selector[] $selectors
     * @param DeclarationBlock $declarationBlock
     */
    public function __construct(public array $selectors, public DeclarationBlock $declarationBlock)
    {
        //
    }

    /**
     * @param Selector|string $selector
     * @return bool
     */
    public function matches(Selector|string $selector): bool
    {
        $selector = ($selector instanceof Selector) ? $selector->selector : $selector;

        $found = array_find(
            $this->selectors,
            fn(Selector $s) => $s->selector === $selector
        );

        return isset($found);
    }

    /**
     * @param StyleSheet $styleSheet
     * @param int $nestLevel
     * @return string
     */
    public function render(StyleSheet $styleSheet, int $nestLevel = 0): string
    {
        return $styleSheet->ruleSetFormat->format($styleSheet, $this->selectors, $this->declarationBlock, $nestLevel);
    }
}
