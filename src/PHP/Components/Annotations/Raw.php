<?php

namespace AVASTech\Demeter\PHP\Components\Annotations;

use AVASTech\Demeter\PHP\Components\Interfaces\AnnotationInterface;

/**
 * Class RawAnnotation
 *
 * @package AVASTech\Demeter\Components\Annotations
 */
class Raw implements AnnotationInterface
{
    /**
     * @var string $content
     */
    protected string $content = '';

    /**
     * @param  string  $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param  string  $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): string
    {
        return sprintf(
            'param::%s',
            md5($this->getContent())
        );
    }

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        return $this->getContent();
    }

    /**
     * @inheritDoc
     */
    public function extractImports(): array
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function applyImportAliasing(array $imports): void
    {
        // do nothing
    }
}
