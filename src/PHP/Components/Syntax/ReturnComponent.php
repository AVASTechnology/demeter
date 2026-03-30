<?php

namespace AVASTech\Demeter\PHP\Components\Syntax;

use AVASTech\Demeter\PHP\Components\AbstractComponent;
use AVASTech\Demeter\PHP\Components\Interfaces\ComponentInterface;
use AVASTech\Demeter\PHP\Definitions\Context;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;
use AVASTech\Demeter\PHP\Definitions\Type;

/**
 * Class ReturnComponent
 *
 * @package AVASTech\Demeter\PHP\Components\Syntax
 */
class ReturnComponent extends AbstractComponent
{
    /**
     * @param bool|string|int|float|array|ComponentInterface|Type $value
     */
    public function __construct(
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
                $this->value instanceof ComponentInterface => $this->value->render($context->increaseLevel()),
                is_array($this->value) => ltrim((new ArrayComponent($this->value))->render($context->increaseLevel())),
                is_string($this->value) => '"' . $this->value . '"',
                is_bool($this->value) => ($this->value ? 'true' : 'false'),
                is_int($this->value) => $this->value,
                default => var_export($this->value, true),
            };
        }

        return sprintf(
            '%sreturn%s;',
                $context?->indentation() ?? '',
            isset($value) ? ' ' . $value : ''
        );
    }
}
