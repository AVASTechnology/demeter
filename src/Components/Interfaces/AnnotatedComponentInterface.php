<?php

namespace AVASTech\Demeter\Components\Interfaces;

use AVASTech\Demeter\Components\Annotations\Section;

/**
 * Interface AnnotatedComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface AnnotatedComponentInterface
{
    /**
     * @param  string  $indentation
     * @return string
     */
    public function renderAnnotation(string $indentation = ''): string;

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
