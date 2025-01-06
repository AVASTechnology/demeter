<?php

namespace AVASTech\Demeter\Components\Annotations;

use AVASTech\Demeter\Components\Interfaces\AnnotationInterface;
use AVASTech\Demeter\Definitions\Import;

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
        return implode(
            ' ',
            [
                $indentation,
                '@' . $this->getDocType(),
                $this->getName(),
            ]
        );


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
