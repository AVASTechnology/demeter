<?php

namespace AVASTech\Demeter\Components;

/**
 * Class RawSyntaxComponent
 *
 * @package AVASTech\Demeter\Components
 *
 */
class RawSyntaxComponent extends AbstractComponent
{
    /**
     * @var string $syntax
     */
    protected string $syntax = '';

    /**
     * RawSyntaxComponent constructor.
     *
     * @param  string  $syntax
     */
    public function __construct(string $syntax)
    {
        $this->syntax = $syntax;
    }

    /**
     * @return string
     */
    public function getSyntax(): string
    {
        return $this->syntax;
    }

    /**
     * @param  string  $syntax
     */
    public function setSyntax(string $syntax): void
    {
        $this->syntax = $syntax;
    }

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        return $indentation . $this->syntax;
    }
}
