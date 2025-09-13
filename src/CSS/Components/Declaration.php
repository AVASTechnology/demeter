<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Components\Interfaces\Property;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;

/**
 * Class Declaration
 *
 * @package AVASTech\Demeter\CSS
 */
class Declaration implements Interfaces\Declaration
{
    /**
     * @param Property $property
     * @param string $value
     */
    public function __construct(public Property $property, public string $value)
    {
        //
    }

    /**
     * @param DeclarationFormat $format
     * @return string
     */
    public function render(DeclarationFormat $format): string
    {
        return $format->format($this);
    }
}
