<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Declaration;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;

/**
 * Class DeclarationBlock
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class DeclarationBlock implements Interfaces\DeclarationBlock
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
     * @var DeclarationFormat|null
     */
    public ?DeclarationFormat $declarationFormat {
        get {
            return $this->declarationFormat ?? null;
        }
        set {
            $this->declarationFormat = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function format(array $declarations, int $nestLevel = 0): string
    {
        if (!isset($this->declarationFormat)) {
            throw new \UnexpectedValueException('Declaration format not set.');
        }

        $indent = $this->indent instanceof \Closure
            ? $this->indent
            : fn() => strval($this->indent);

        $endOfLine = $this->endOfLine instanceof \Closure
            ? $this->endOfLine
            : fn() => strval($this->endOfLine);

        return sprintf(
            "{%s%s}%s",
            $endOfLine(null),
            implode(
                '',
                array_map(
                    function (Declaration $declaration) use ($indent, $endOfLine) {
                        return sprintf(
                            '%s%s%s',
                            $indent($declaration),
                            $declaration->render($this->declarationFormat),
                            $endOfLine($declaration),
                        );
                    },
                    $declarations
                )
            ),
            $endOfLine(null),
        );
    }
}
