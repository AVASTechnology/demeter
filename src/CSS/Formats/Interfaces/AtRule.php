<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier;
use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock;

/**
 * Interface AtRule
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface AtRule extends Statement, StatementSet
{
    /**
     * @param StyleSheet $styleSheet
     * @param AtRuleIdentifier $identifier
     * @param string $rule
     * @param array|DeclarationBlock|null $blockStatements
     * @param int $nestLevel
     * @return string
     */
    public function format(
        StyleSheet $styleSheet,
        AtRuleIdentifier $identifier,
        string $rule,
        array|DeclarationBlock|null $blockStatements,
        int $nestLevel = 0
    ): string;
}
