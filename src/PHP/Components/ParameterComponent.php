<?php

namespace AVASTech\Demeter\PHP\Components;

use AVASTech\Demeter\PHP\Components\Traits\HasName;
use AVASTech\Demeter\PHP\Components\Traits\HasTypes;
use AVASTech\Demeter\PHP\Definitions\Type;
use AVASTech\Demeter\PHP\Definitions\TypeSet;

/**
 * Class ParameterComponent
 *
 * @package AVASTech\Demeter\Components
 */
class ParameterComponent extends AbstractComponent
{
    use HasName;
    use HasTypes;

    /**
     * @var mixed $default
     */
    protected $default;

    /**
     * PropertyComponent constructor.
     *
     * @param  string  $name
     * @param  array|null  $types
     * @param  string|null  $description
     */
    public function __construct(string $name, array $types = null, string $description = null)
    {
        $this->name     = $name;
        $this->types    = new TypeSet($types ?? [Type::BUILT_IN_MIXED]);

        $this->setDescription($description ?? $this->description);
    }

    /**
     * @return mixed
     */
    public function getDefault(): mixed
    {
        return $this->default;
    }

    /**
     * @param  mixed  $default
     */
    public function setDefault(mixed $default): void
    {
        $this->default = $default;
    }

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        $parts = [
            $this->getTypeString(),
            '$' . $this->name,
        ];

        if (!is_null($this->default)) {
            $parts[] = '=';
            $parts[] = var_export($this->default, true);
        }

        return implode(' ', array_filter($parts));
    }
}
