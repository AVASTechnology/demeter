<?php

namespace AVASTech\Demeter\PHP\Components\Traits;

use AVASTech\Demeter\PHP\Definitions\Type;
use AVASTech\Demeter\PHP\Definitions\TypeSet;

/**
 * Trait HasParent
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasTypes
{
    /**
     * @var TypeSet $type
     */
    protected TypeSet $types;

    /**
     * @return TypeSet
     */
    public function getTypeSet(): TypeSet
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getTypes(): array
    {
        return $this->types->get();
    }

    /**
     * @param  array  $types
     */
    public function setTypes(array $types): void
    {
        $this->types->set($types);
    }

    /**
     * @param  Type|string  $type
     */
    public function addType(Type|string $type): void
    {
        $this->types->add($type);
    }

    /**
     * @param  bool  $asAnnotation
     * @return string
     */
    public function getTypeString(bool $asAnnotation = false): string
    {
        return ($asAnnotation
            ? $this->types->asAnnotation()
            : $this->types->__toString()
        );
    }
}
