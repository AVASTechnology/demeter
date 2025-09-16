<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class Comment
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class Comment implements Interfaces\Comment
{
    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $indent {
        get {
            return $this->indent ?? null;
        }
        set {
            $this->indent = $value;
        }
    }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $endOfLine {
        get {
            return $this->endOfLine ?? null;
        }
        set {
            $this->endOfLine = $value;
        }
    }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $onNewStatement {
        get {
            return $this->onNewStatement ?? null;
        }
        set {
            $this->onNewStatement = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function format(StyleSheet $styleSheet, string $content, int $nestLevel = 0): string
    {
        $indent = $this->indent ?? $styleSheet->indent;
        $endOfLine = $this->endOfLine ?? $styleSheet->endOfLine;
        $onNewStatement = $this->onNewStatement ?? $styleSheet->onNewStatement;

        return sprintf(
            '%s/*%s%s%s%s%s*/%s',
            $indent,
            $endOfLine,
            $indent,
            $content,
            $endOfLine,
            $indent,
            $onNewStatement,
        );
    }
}
