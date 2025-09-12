<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;

/**
 * Class Comment
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class Comment implements Interfaces\Comment
{
    /**
     * @param string $text
     */
    public function __construct(public string $text)
    {
        //
    }

    /**
     * @param CommentFormat $format
     * @param int $nestLevel
     * @return string
     */
    public function render(CommentFormat $format, int $nestLevel = 0): string
    {
        return $format->format($this->text, $nestLevel);
    }
}
