<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\Declaration as DeclarationInterface;

/**
 * Interface Declaration
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
interface Declaration
{
    /**
     * @param DeclarationInterface $declaration
     * @return string
     */
    public function format(DeclarationInterface $declaration): string;
}
