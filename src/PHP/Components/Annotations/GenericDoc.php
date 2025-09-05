<?php

namespace AVASTech\Demeter\PHP\Components\Annotations;

use AVASTech\Demeter\PHP\Components\Interfaces\AnnotationInterface;
use AVASTech\Demeter\PHP\Definitions\Import;

/**
 * Class GenericType
 *
 * @package AVASTech\Demeter\Components\Annotations
 */
class GenericDoc implements AnnotationInterface
{
    /**
     * GenericDoc constructor.
     *
     * @param  string  $docType
     * @param  string  $name
     */
    public function __construct(protected string $docType, protected string $name)
    {
        //
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return $this->docType;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param  string  $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return sprintf(
            '%s::%s',
            $this->getDocType(),
            $this->getName()
        );
    }

    /**
     * @param  string  $indentation
     * @return string
     */
    public function render(string $indentation = ''): string
    {
        return '';
    }

    /**
     * @return array|Import[]
     */
    public function extractImports(): array
    {
        return [];
    }

    /**
     * @param  array  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void
    {
        //
    }
}
