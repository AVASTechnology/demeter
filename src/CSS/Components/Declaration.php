<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Components\Interfaces\Property;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

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
     * @param StyleSheet $styleSheet
     * @return string
     */
    public function render(StyleSheet $styleSheet): string
    {
        return $styleSheet->declarationFormat->format($styleSheet, $this);
    }
}
