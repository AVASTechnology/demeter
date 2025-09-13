<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\Declaration as DeclarationInterface;

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
    public function format(DeclarationInterface $declaration): string
    {
        return sprintf(
            '%s: %s;',
            $declaration->property->value,
            $declaration->value,
        );
    }
}
