<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class DeclarationBlock
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class DeclarationBlock implements Interfaces\DeclarationBlock
{
    /**
     * @param array|Declaration[] $declarations
     */
    public function __construct(public array $declarations)
    {
        //
    }

    public function addDeclaration(Declaration $declaration): void
    {
        $this->declarations[] = $declaration;
    }

    /**
     * @param Interfaces\Property $property
     * @return void
     */
    public function removeDeclaration(Interfaces\Property $property): void
    {
        $this->declarations = array_filter(
            $this->declarations,
            fn(Declaration $declaration) => $declaration->property->value !== $property->value
        );
    }

    /**
     * @return void
     */
    public function sort(): void
    {
        usort($this->declarations, fn(Declaration $a, Declaration $b) => $a->property->value <=> $b->property->value);
    }

    /**
     * @param StyleSheet $styleSheet
     * @return string
     */
    public function render(StyleSheet $styleSheet): string
    {
        $this->sort();

        $declarations = array_combine(
            array_map(fn(Declaration $declaration) => $declaration->property->value, $this->declarations),
            $this->declarations
        );

        return $styleSheet->declarationBlockFormat->format($styleSheet, $declarations);
    }

    /**
     * @return array
     */
    public function toDictionary(): array
    {
        return array_combine(
            array_map(fn(Declaration $declaration) => $declaration->property->value, $this->declarations),
            array_map(fn(Declaration $declaration) => $declaration->value, $this->declarations),
        );
    }
}
