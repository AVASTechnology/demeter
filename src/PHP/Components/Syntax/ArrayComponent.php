<?php

namespace AVASTech\Demeter\PHP\Components\Syntax;

use AVASTech\Demeter\PHP\Components\AbstractComponent;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;

/**
 * Class ArrayComponent
 *
 * @package AVASTech\Demeter\PHP\Components\Syntax
 */
class ArrayComponent extends AbstractComponent
{
    /**
     * @param array $array
     * @param bool $short
     */
    public function __construct(public array $array, public bool $short = true)
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function render(?ContextInterface $context = null): string
    {
        $indentation = $context?->indentation() ?? '';
        $lineIndentation = $context?->increaseLevel()?->indentation() ?? '    ';

        $lines = [];

        foreach ($this->array as $key => $value) {
            if (is_string($key)) {
                $key = "'" . addslashes($key) . "'";
            }

            $lines[] = sprintf(
                '%s%s => %s,',
                $lineIndentation,
                    $key,
                    $value instanceof AbstractComponent ? $value->render($context) : var_export($value, true),
            );
        }

        return sprintf(
            "%s%s\n%s\n%s%s",
            $indentation,
            $this->short ? '[' : 'array(',
            implode(
                "\n",
                $lines
            ),
            $indentation,
            $this->short ? ']' : ')'
        );
    }
}
