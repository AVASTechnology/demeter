<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\Statement;

/**
 * Interface StyleSheet
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface StyleSheet extends StatementSet
{
    /**
     * @param Statement[] $statements
     * @return string
     */
    public function format(array $statements): string;
}
