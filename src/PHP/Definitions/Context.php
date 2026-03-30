<?php

namespace AVASTech\Demeter\PHP\Definitions;

use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;

/**
 * Class Context
 *
 * @package AVASTech\Demeter\PHP\Definitions
 */
readonly class Context implements ContextInterface
{
    /**
     * @param int $level
     * @param string $indent
     */
    public function __construct(
        public int $level = 0,
        public string $indent = '   '
    ) {
        //
    }

    /**
     * @param string $indent
     * @return static
     */
    public static function fromIndent(string $indent): static
    {
        return new static(0, $indent);
    }

    /**
     * @return $this
     */
    public function increaseLevel(): static
    {
        return new static($this->level + 1, $this->indent);
    }

    /**
     * @return string
     */
    public function indentation(): string
    {
        return str_repeat($this->indent, $this->level);
    }
}
