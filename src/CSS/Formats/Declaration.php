<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\Declaration as DeclarationInterface;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class Declaration
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class Declaration implements Interfaces\Declaration
{
    /**
     * @inheritDoc
     */
    public function format(StyleSheet $styleSheet, DeclarationInterface $declaration): string
    {
        return sprintf(
            '%s: %s;',
            $declaration->property->value,
            $declaration->value,
        );
    }
}
