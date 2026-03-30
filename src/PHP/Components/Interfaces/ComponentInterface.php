<?php

namespace AVASTech\Demeter\PHP\Components\Interfaces;

use AVASTech\Demeter\PHP\Definitions\Import;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;

/**
 * Interface AnnotatedComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface ComponentInterface
{
    /**
     * @param ContextInterface|null $context
     * @return string
     */
    public function render(?ContextInterface $context = null): string;

    /**
     * @return Import[]
     */
    public function extractImports(): array;

    /**
     * @param  Import[]  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void;
}
