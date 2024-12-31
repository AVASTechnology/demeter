<?php

namespace AVASTech\Demeter\Components\Annotations;

use AVASTech\Demeter\Components\Interfaces\AnnotationInterface;
use AVASTech\Demeter\Components\Traits\HasName;
use AVASTech\Demeter\Components\Traits\HasTypes;
use AVASTech\Demeter\Definitions\Import;
use AVASTech\Demeter\Definitions\TypeSet;

/**
 * Class AbstractTypeAnnotation
 *
 * @package AVASTech\Demeter\Components\Annotations
 */
abstract class AbstractTypeAnnotation implements AnnotationInterface
{
    use HasName;
    use HasTypes;

    /**
     * @var string DOC_TYPE
     */
    const DOC_TYPE = 'generic';

    /**
     * AbstractTypeAnnotation constructor.
     *
     * @param  string  $name
     * @param  array  $types
     */
    public function __construct(string $name, array $types = [])
    {
        $this->name = $name;
        $this->types = new TypeSet($types);
    }

    /**
     * @return string
     */
    public function getDocType(): string
    {
        return static::DOC_TYPE;
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
     * @return Import[]
     */
    public function extractImports(): array
    {
        $imports = [];

        $this->types->map(
            function ($type) use (&$imports) {
                if ($type instanceof \UnitEnum) {
                    return $type;
                }

                if (!($type instanceof Import)) {
                    $type = new Import($type);
                }

                $imports[] = $type;

                return $type;
            }
        );

        return $imports;
    }

    /**
     * @param  array  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void
    {
        $this->types->map(
            function ($type) use ($imports) {
                return ($type instanceof Import && isset($imports[$type->getIdentifier()])
                    ? $imports[$type->getIdentifier()]
                    : $type
                );
            }
        );
    }

    public function render(string $indentation = ''): string
    {
        return '';
    }
}
