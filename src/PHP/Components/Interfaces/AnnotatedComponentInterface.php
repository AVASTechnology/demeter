<?php

namespace AVASTech\Demeter\PHP\Components\Interfaces;

use AVASTech\Demeter\PHP\Components\Annotations\Section;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;

/**
 * Interface AnnotatedComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface AnnotatedComponentInterface
{
    /**
     * @param ContextInterface|null $context
     * @return string
     */
    public function renderAnnotation(?ContextInterface $context = null): string;

    /**
     * @return Section
     */
    public function getAnnotationSection(): Section;

    /**
     * @param  Section  $annotationSection
     */
    public function setAnnotationSection(Section $annotationSection): void;

    /**
     * @param  AnnotationInterface  $annotation
     * @return void
     */
    public function addAnnotation(AnnotationInterface $annotation);

    /**
     * Integrate Component dependencies into Annotation
     * @return Section
     */
    public function compileAnnotation(): Section;
}
