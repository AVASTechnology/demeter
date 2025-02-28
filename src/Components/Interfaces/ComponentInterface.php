<?php

namespace AVASTech\Demeter\Components\Interfaces;

use AVASTech\Demeter\Definitions\Import;

/**
 * Interface AnnotatedComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface ComponentInterface
{
    /**
     * @param  string  $indentation
     * @return string
     */
    public function render(string $indentation = ''): string;

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
