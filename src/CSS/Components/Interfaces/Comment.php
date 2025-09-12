<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;

/**
 * Interface Comment
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface Comment extends Statement
{
    /**
     * @param CommentFormat $format
     * @param int $nestLevel
     * @return string
     */
    public function render(CommentFormat $format, int $nestLevel = 0): string;
}
