<?php

namespace AVASTech\Demeter\PHP\Definitions\Interfaces;

/**
 * Interface ContextInterface
 *
 * @package AVASTech\Demeter\PHP\Definitions\Interfaces
 */
interface ContextInterface
{
    /**
     * @var int $level
     */
    public int $level { get; }

    /**
     * @var string $indent
     */
    public string $indent { get; }

    /**
     * @param string $indent
     * @return static
     */
    public static function fromIndent(string $indent): static;

    /**
     * @return static
     */
    public function increaseLevel(): static;

    /**
     * @return string
     */
    public function indentation(): string;
}
