<?php

namespace AVASTech\Demeter\PHP\Definitions;

/**
 * Class ClassReference
 *
 * @package AVASTech\Demeter\Definitions
 */
class ClassReference
{
    /**
     * @var array $namespaceParts
     */
    protected array $namespaceParts = [];

    /**
     * @var string $className
     */
    protected string $className;

    /**
     * @param  string  $name
     */
    public function __construct(string $name)
    {
        $parts = array_filter(explode('\\', $name));

        $this->className = array_pop($parts);
        $this->namespaceParts = $parts;
    }

    /**
     * @return string
     */
    public function className(): string
    {
        return $this->className;
    }

    /**
     * @return string
     */
    public function namespace(): string
    {
        return implode('\\', $this->namespaceParts);
    }

    /**
     * @return string
     */
    public function fullyQualifiedClassName(): string
    {
        return sprintf(
            '\\%s\\%s',
            $this->namespace(),
            $this->className()
        );
    }

    /**
     * @return string
     */
    public function aliasName(): string
    {
        return sprintf(
            '%s%s',
            $this->className,
            end($this->namespaceParts)
        );
    }

    /**
     * @param  string|ClassReference  $reference
     * @return bool
     */
    public function equal(string|ClassReference $reference): bool
    {
        return ($this->fullyQualifiedClassName() === '\\' . ltrim($reference, '\\'));
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->fullyQualifiedClassName();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->fullyQualifiedClassName();
    }
}
