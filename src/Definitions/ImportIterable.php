<?php

namespace AVASTech\Demeter\Definitions;

/**
 * Class ImportIterable
 *
 * @package AVASTech\Demeter\Definitions
 */
class ImportIterable extends Import
{
    /**
     * @return string
     */
    public function importedAs(): string
    {
        return $this->isAliased() ? $this->getAlias() : $this->className();
    }

    /**
     * @return string
     */
    public function annotatedAs(): string
    {
        return sprintf('%s[]', $this->isAliased() ? $this->getAlias() : $this->className());
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->fullyQualifiedClassName() . '[]';
    }
}
