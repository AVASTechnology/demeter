<?php

namespace AVASTech\Demeter\Components\Traits;

/**
 * Trait HasAccessScoping
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasAccessScoping
{
    /**
     * @var bool $staticScope
     */
    protected bool $staticScope = false;

    /**
     * @return bool
     */
    public function isStatic(): bool
    {
        return $this->staticScope;
    }

    /**
     * @param  bool  $staticScope
     */
    public function setStatic(bool $staticScope)
    {
        $this->staticScope = $staticScope;
    }
}
