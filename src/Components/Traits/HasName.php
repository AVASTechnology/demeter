<?php

namespace AVASTech\Demeter\Components\Traits;

/**
 * Trait HasName
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasName
{
    /**
     * @var string
     */
    protected string $description = '';

    /**
     * @var string $name
     */
    protected string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param  string  $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
