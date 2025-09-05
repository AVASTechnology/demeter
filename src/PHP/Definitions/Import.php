<?php

namespace AVASTech\Demeter\PHP\Definitions;

/**
 * Class Import
 *
 * @package AVASTech\Demeter\Definitions
 */
class Import extends ClassReference
{
    /**
     * @var string|null $alias
     */
    protected ?string $alias;

    /**
     * @return string
     */
    public function importedAs(): string
    {
        return $this->isAliased() ? $this->getAlias() : $this->className();
    }

    /**
     * @return bool
     */
    public function isAliased(): bool
    {
        return isset($this->alias);
    }

    /**
     * @return string|null
     */
    public function getAlias(): ?string
    {
        return $this->alias;
    }

    /**
     * @param  string|null  $alias
     */
    public function setAlias(?string $alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return string
     */
    public function predictAlias(): string
    {
        // make sure not to edit the original
        $parts = $this->namespaceParts;

        return sprintf(
            '%s%s',
            array_pop($parts),
            array_pop($parts),
        );
    }
}
