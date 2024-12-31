<?php

namespace AVASTech\Demeter\Components\Traits;

use AVASTech\Demeter\Definitions\Import;

/**
 * Trait HasInterfaces
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasInterfaces
{
    /**
     * @var array $interfaces
     */
    protected array $interfaces = [];

    /**
     * @return Import[]
     */
    public function getInterfaces(): array
    {
        return $this->interfaces;
    }

    /**
     * @param  Import[]  $interfaces
     */
    public function setInterfaces(array $interfaces): void
    {
        $this->interfaces = $interfaces;
    }

    /**
     * @param  Import|string  $interface
     */
    public function addInterface(Import|string $interface): void
    {
        $this->interfaces[] = (is_string($interface) ? new Import($interface) : $interface);
    }
}
