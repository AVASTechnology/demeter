<?php

namespace AVASTech\Demeter\Components\Interfaces;

/**
 * Interface AccessScopingComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface AccessScopingComponentInterface
{
    /**
     * @return bool
     */
    public function isStatic(): bool;

    /**
     * @param  bool  $staticScope
     */
    public function setStatic(bool $staticScope);
}
