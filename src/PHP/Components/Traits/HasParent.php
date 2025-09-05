<?php

namespace AVASTech\Demeter\PHP\Components\Traits;

use AVASTech\Demeter\PHP\Definitions\Import;

/**
 * Trait HasParent
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasParent
{
    /**
     * @var Import|null $extends
     */
    protected ?Import $extends;

    /**
     * @return Import|null
     */
    public function getExtends(): ?Import
    {
        return $this->extends;
    }

    /**
     * @param  Import|string|null  $extends
     */
    public function setExtends(string|Import|null $extends): void
    {
        $this->extends = (is_string($extends) ? new Import($extends) : $extends);
    }
}
