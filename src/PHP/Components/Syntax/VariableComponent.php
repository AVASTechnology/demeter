<?php

namespace AVASTech\Demeter\PHP\Components\Syntax;

use AVASTech\Demeter\PHP\Components\AbstractComponent;
use AVASTech\Demeter\PHP\Components\Interfaces\ComponentInterface;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;
use AVASTech\Demeter\PHP\Definitions\Type;

/**
 * Class VariableComponent
 *
 * @package AVASTech\Demeter\PHP\Components\Syntax
 */
class VariableComponent extends AbstractComponent
{
    /**
     * @param string $name
     * @param bool|string|int|float|array|ComponentInterface|Type $value
     */
    public function __construct(
        public string $name,
        public bool|string|int|float|array|ComponentInterface|Type $value = Type::BUILT_IN_VOID
    ) {
        //
    }

    /**
     * @inheritDoc
     */
    public function render(?ContextInterface $context = null): string
    {
        $value = null;

        if ($this->value !== Type::BUILT_IN_VOID) {
            $value = match (true) {
                $this->value instanceof ComponentInterface => $this->value->render($context),
                is_array($this->value) => ltrim((new ArrayComponent($this->value))->render($context)),
                is_string($this->value) => '"' . $this->value . '"',
                is_bool($this->value) => ($this->value ? 'true' : 'false'),
                is_int($this->value) => $this->value,
                default => var_export($this->value, true),
            };
        }

        return sprintf(
            '%s$%s%s;',
                $context?->indentation() ?? '',
            $this->name,
            isset($value) ? ' = ' . $value : ''
        );
    }
}
