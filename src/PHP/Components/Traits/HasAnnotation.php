<?php

namespace AVASTech\Demeter\PHP\Components\Traits;

use AVASTech\Demeter\PHP\Components\Annotations\Section;
use AVASTech\Demeter\PHP\Components\Interfaces\AnnotationInterface;

/**
 * Trait HasAnnotation
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasAnnotation
{
    /**
     * @var Section $annotationSection
     */
    protected Section $annotationSection;

    /**
     * @return Section
     */
    public function getAnnotationSection(): Section
    {
        if (!isset($this->annotationSection)) {
            $this->annotationSection = new Section();
        }

        return $this->annotationSection;
    }

    /**
     * @param  Section  $annotationSection
     */
    public function setAnnotationSection(Section $annotationSection): void
    {
        $this->annotationSection = $annotationSection;
    }

    /**
     * @param  AnnotationInterface  $annotation
     * @return void
     */
    public function addAnnotation(AnnotationInterface $annotation)
    {
        $this->getAnnotationSection()->addAnnotation($annotation);
    }

    /**
     * @param  string  $identifier
     * @return AnnotationInterface|null
     */
    public function findAnnotation(string $identifier): ?AnnotationInterface
    {
        return $this->getAnnotationSection()->findAnnotation($identifier);
    }

    /**
     * @param  string  $indentation
     * @return string
     */
    public function renderAnnotation(string $indentation = ''): string
    {
        return $this->getAnnotationSection()->render($indentation);
    }
}
