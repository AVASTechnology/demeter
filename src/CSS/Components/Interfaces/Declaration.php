<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;

/**
 * Interface Declaration
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 *
 * @property Property $property
 * @property string $value
 */
interface Declaration
{
    /**
     * @param Property $property
     * @param string $value
     */
    public function __construct(Property $property, string $value);

    /**
     * @param DeclarationFormat $format
     * @return string
     */
    public function render(DeclarationFormat $format): string;
}
