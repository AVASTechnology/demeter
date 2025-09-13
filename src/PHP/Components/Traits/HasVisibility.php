<?php

namespace AVASTech\Demeter\PHP\Components\Traits;

use AVASTech\Demeter\PHP\Components\Interfaces\VisibilityComponentInterface;

/**
 * Trait HasVisibility
 *
 * @package AVASTech\Demeter\Components\Traits
 */
trait HasVisibility
{
    /**
     * @var string $visibility
     */
    protected string $visibility = VisibilityComponentInterface::VISIBILITY_PUBLIC;

    /**
     * @return bool
     */
    public function isPublic(): bool
    {
        return ($this->visibility === VisibilityComponentInterface::VISIBILITY_PUBLIC);
    }

    /**
     * @return bool
     */
    public function isProtected(): bool
    {
        return ($this->visibility === VisibilityComponentInterface::VISIBILITY_PROTECTED);
    }

    /**
     * @return bool
     */
    public function isPrivate(): bool
    {
        return ($this->visibility === VisibilityComponentInterface::VISIBILITY_PRIVATE);
    }

    /**
     * @return string
     */
    public function getVisibility(): string
    {
        return $this->visibility;
    }

    /**
     * @param  string  $visibility
     */
    public function setVisibility(string $visibility)
    {
        if (!isset(VisibilityComponentInterface::VISIBILITY_TYPES[$visibility])) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Invalid visibility "%s".',
                    $visibility
                )

            );
        }

        $this->visibility = $visibility;
    }
}
