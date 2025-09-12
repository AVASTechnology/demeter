<?php

namespace AVASTech\Demeter\CSS\Formats;

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
    public function format(string $content, int $nestLevel = 0): string
    {
        return sprintf(
            '%s/*%s%s%s%s%s*/%s',
            $this->indent,
            $this->endOfLine,
            $this->indent,
            $content,
            $this->endOfLine,
            $this->indent,
            $this->onNewStatement,
        );
    }
}
