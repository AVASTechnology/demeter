<?php

namespace AVASTech\Demeter\Components\Traits;

/**
 * Trait HasInheritanceScoping
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasInheritanceScoping
{
    /**
     * @var bool $abstract
     */
    protected bool $abstract = false;

    /**
     * @var bool $final
     */
    protected bool $final = false;

    /**
     * @return bool
     */
    public function isAbstract(): bool
    {
        return $this->abstract;
    }

    /**
     * @param  bool  $abstract
     */
    public function setAbstract(bool $abstract): void
    {
        $this->abstract = $abstract;
    }

    /**
     * @return bool
     */
    public function isFinal(): bool
    {
        return $this->final;
    }

    /**
     * @param  bool  $final
     */
    public function setFinal(bool $final): void
    {
        $this->final = $final;
    }
}
