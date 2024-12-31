<?php

namespace AVASTech\Demeter\Components\Interfaces;

/**
 * Interface AnnotationInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface AnnotationInterface extends ComponentInterface
{
    /**
     * @return string
     */
    public function getIdentifier(): string;
}
