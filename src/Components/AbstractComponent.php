<?php

namespace AVASTech\Demeter\Components;

use AVASTech\Demeter\Components\Interfaces\ComponentInterface;
use AVASTech\Demeter\Definitions\Import;

/**
 * Class AbstractComponent
 *
 * @package AVASTech\Demeter\Components
 */
abstract class AbstractComponent implements ComponentInterface
{
    /**
     * @param  string  $indentation
     * @return string
     */
    abstract public function render(string $indentation = ''): string;

    /**
     * @return Import[]
     */
    public function extractImports(): array
    {
        return [];
    }

    /**
     * @param  Import[]  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void
    {
        //
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * @param  string  $className
     * @return array
     */
    protected function explodeClassName(string $className): array
    {
        $pathInfo = pathinfo(str_replace('\\', '/', $className));

        return [
            'namespace' => str_replace('/', '\\', $pathInfo['dirname']),
            'class'     => $pathInfo['basename'],
            'fqcn'      => $className,
        ];
    }
}
