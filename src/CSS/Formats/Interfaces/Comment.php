<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

/**
 * Interface Comment
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface Comment extends Statement
{
    /**
     * @param string $content
     * @return string
     */
    public function format(string $content, int $nestLevel = 0): string;
}
