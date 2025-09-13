<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;

/**
 * Interface DeclarationBlock
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface DeclarationBlock
{
    /**
     * @var array<Declaration> $declarations
     */
    public array $declarations { get; set; }

    /**
     * @param Property $property
     * @return void
     */
    public function removeDeclaration(Property $property): void;

    /**
     * @param DeclarationBlockFormat $format
     * @return string
     */
    public function render(DeclarationBlockFormat $format): string;

    /**
     * @return array
     */
    public function toDictionary(): array;
}
