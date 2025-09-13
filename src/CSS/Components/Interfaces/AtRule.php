<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;

/**
 * Interface AtRule
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface AtRule extends Statement
{
    /**
     * @param AtRuleFormat $format
     * @param int $nestLevel
     * @return string
     */
    public function render(AtRuleFormat $format, int $nestLevel = 0): string;
}
