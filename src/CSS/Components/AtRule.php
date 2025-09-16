<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier as AtRuleIdentifier;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class AtRule
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class AtRule implements Interfaces\AtRule
{
    public function __construct(
        public AtRuleIdentifier $identifier,
        public ?string $rule,
        public array|Interfaces\DeclarationBlock|null $blockStatements = null
    ) {
        //
    }

    public function removeAtRule(Interfaces\AtRuleIdentifier $identifier): void
    {
        if (is_array($this->blockStatements)) {
            $this->blockStatements = array_filter(
                $this->blockStatements,
                function ($statement) use ($identifier) {
                    if (!($statement instanceof AtRule) || !$statement->identifier->isBlock()) {
                        // keep all non-at-rule statements and non-statement at-rules
                        return true;
                    } elseif ($statement->identifier === $identifier) {
                        // AtRule is the identifier we're looking for, remove it'
                        return false;
                    }

                    // Nested AtRules
                    $statement->removeAtRule($identifier);

                    // Keep if at-rule still has statements
                    return !empty($statement->blockStatements);
                }
            );
        }
    }

    /**
     * @param StyleSheet $styleSheet
     * @param int $nestLevel
     * @return string
     */
    public function render(StyleSheet $styleSheet, int $nestLevel = 0): string
    {
        return $styleSheet->atRuleFormat->format($styleSheet, $this->identifier, $this->rule, $this->blockStatements, $nestLevel);
    }
}
