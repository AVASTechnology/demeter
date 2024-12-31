<?php

namespace AVASTech\Demeter\Components\Interfaces;

/**
 * Interface VisibilityComponentInterface
 *
 * @package AVASTech\Demeter\Components\Interfaces
 */
interface VisibilityComponentInterface
{
    public const string VISIBILITY_PUBLIC    = 'public';
    public const string VISIBILITY_PROTECTED = 'protected';
    public const string VISIBILITY_PRIVATE   = 'private';

    public const array VISIBILITY_TYPES = [
        self::VISIBILITY_PUBLIC    => 'Public',
        self::VISIBILITY_PROTECTED => 'Protected',
        self::VISIBILITY_PRIVATE   => 'Private',
    ];

    /**
     * @return bool
     */
    public function isPublic(): bool;

    /**
     * @return bool
     */
    public function isProtected(): bool;

    /**
     * @return bool
     */
    public function isPrivate(): bool;

    /**
     * @return string
     */
    public function getVisibility(): string;

    /**
     * @param  string  $visibility
     */
    public function setVisibility(string $visibility);
}
