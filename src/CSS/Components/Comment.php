<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

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
     * @param StyleSheet $styleSheet
     * @param int $nestLevel
     * @return string
     */
    public function render(StyleSheet $styleSheet, int $nestLevel = 0): string
    {
        return $styleSheet->commentFormat->format($styleSheet, $this->text, $nestLevel);
    }
}
