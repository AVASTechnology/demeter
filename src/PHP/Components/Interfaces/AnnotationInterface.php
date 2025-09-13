<?php

namespace AVASTech\Demeter\PHP\Components\Interfaces;

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
