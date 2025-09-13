<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;

/**
 * Interface RuleSet
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface RuleSet extends Statement
{
    /**
     * @var array<Selector> $selectors
     */
    public array $selectors { get; set; }

    /**
     * @var DeclarationBlock $declarationBlock
     */
    public DeclarationBlock $declarationBlock { get; set; }

    /**
     * @param array $selectors
     * @param DeclarationBlock $declarationBlock
     */
    public function __construct(array $selectors, DeclarationBlock $declarationBlock);

    /**
     * @param RuleSetFormat $format
     * @param int $nestLevel
     * @return string
     */
    public function render(RuleSetFormat $format, int $nestLevel = 0): string;
}
