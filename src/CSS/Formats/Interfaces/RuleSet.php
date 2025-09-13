<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock as DeclarationBlockInterface;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector as SelectorInterface;
use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

/**
 * Interface RuleSet
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface RuleSet extends Statement
{
    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $selectorSpacing { get; set; }

    /**
     * @var DeclarationBlockFormat|null
     */
    public ?DeclarationBlockFormat $declarationBlockFormat { get; set; }

    /**
     * @var SelectorFormat|null
     */
    public ?SelectorFormat $selectorFormat { get; set; }

    /**
     * @param SelectorInterface[] $selectors
     * @param DeclarationBlockInterface $declarationBlock
     * @return string
     */
    public function format(array $selectors, DeclarationBlockInterface $declarationBlock, int $nestLevel = 0): string;
}
