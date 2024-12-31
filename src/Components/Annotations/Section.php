<?php

namespace AVASTech\Demeter\Components\Annotations;

use AVASTech\Demeter\Components\Interfaces\AnnotationInterface;
use AVASTech\Demeter\Definitions\Import;

/**
 * Class Section
 *
 * @package AVASTech\Demeter\Components\Annotations
 */
class Section implements AnnotationInterface
{
    /**
     * @var string $title
     */
    protected $title;

    /**
     * @var AnnotationInterface[] $annotations
     */
    protected $annotations = [];

    /**
     * AnnotationSectionComponent constructor.
     *
     * @param  AnnotationInterface[]  $annotations
     * @param  string|null  $title
     */
    public function __construct(array $annotations = [], string $title = null)
    {
        $this->setAnnotations($annotations);

        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->title ?? '';
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param  string|null  $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return AnnotationInterface[]
     */
    public function getAnnotations(): array
    {
        return $this->annotations;
    }

    /**
     * @param  AnnotationInterface[]  $annotations
     */
    public function setAnnotations(array $annotations): void
    {
        $this->annotations = [];

        foreach ($annotations as $annotation) {
            $this->addAnnotation($annotation);
        }
    }

    /**
     * @param  AnnotationInterface  $annotation
     */
    public function addAnnotation(AnnotationInterface $annotation)
    {
        $this->annotations[$annotation->getIdentifier()] = $annotation;
    }

    /**
     * @param  string  $identifier
     * @return AnnotationInterface|null
     */
    public function findAnnotation(string $identifier): ?AnnotationInterface
    {
        return $this->annotations[$identifier] ?? null;
    }

    /**
     * @param  \Closure|null  $callback
     * @return bool
     */
    public function sortAnnotations(\Closure $callback = null): bool
    {
        if (!is_callable($callback)) {
            $callback = function (AnnotationInterface $a, AnnotationInterface $b) {
                return $a->getIdentifier() <=> $b->getIdentifier();
            };
        }

        return usort($this->annotations, $callback);
    }

    /**
     * @return Import[]
     */
    public function extractImports(): array
    {
        $imports = [];

        foreach ($this->annotations as $annotation) {
            array_push($imports, ...array_values($annotation->extractImports()));
        }

        return $imports;
    }

    /**
     * @param  array  $imports
     * @return void
     */
    public function applyImportAliasing(array $imports): void
    {
        foreach ($this->annotations as $annotation) {
            $annotation->applyImportAliasing($imports);
        }
    }

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        $renderedAnnotations = [];

        if (!empty($this->getTitle())) {
            $renderedAnnotations[] = sprintf(
                '%s*****%s****',
                $indentation,
                $this->getTitle()
            );
        }

        foreach ($this->annotations as $annotation) {
            $renderedAnnotations[] = $annotation->render($indentation);
        }

        return implode("\n", $renderedAnnotations);
    }
}
