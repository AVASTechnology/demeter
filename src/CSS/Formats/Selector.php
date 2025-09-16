<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class Selector
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class Selector implements Interfaces\Selector
{
    /**
     * @inheritDoc
     */
    public function format(StyleSheet $styleSheet, string $selector): string
    {
        return $selector;
    }
}
