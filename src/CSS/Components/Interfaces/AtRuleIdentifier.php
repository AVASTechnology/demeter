<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

/**
 * Interface AtRuleIdentifier
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 * @mixin \BackedEnum
 */
interface AtRuleIdentifier
{
    /**
     * @param string $css
     * @return self
     */
    public static function determineIdentifier(string $css): self;

    /**
     * @return bool
     */
    public function isStatement(): bool;

    /**
     * @return bool
     */
    public function isBlock(): bool;

    /**
     * @return bool Wrap RULE in parentheses
     */
    public function wrapRule(): bool;
}
