<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier;

/**
 * Interface AtRule
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface AtRule extends Statement, StatementSet
{
    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $selectorSpacing { get; set; }

    /**
     * @param AtRuleIdentifier $identifier
     * @param string $rule
     * @param ?array $blockStatements
     * @param int $nestLevel
     * @return string
     */
    public function format(AtRuleIdentifier $identifier, string $rule, ?array $blockStatements, int $nestLevel = 0): string;
}
