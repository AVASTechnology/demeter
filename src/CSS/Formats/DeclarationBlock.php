<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Declaration;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

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
    public function format(StyleSheet $styleSheet, array $declarations, int $nestLevel = 0): string
    {
        if (!isset($this->declarationFormat)) {
            throw new \UnexpectedValueException('Declaration format not set.');
        }

        $indent = $this->indent ?? $styleSheet->indent;
        $indent = $indent instanceof \Closure
            ? $indent
            : fn() => strval($indent);

        $endOfLine = $this->endOfLine ?? $styleSheet->indent;
        $endOfLine = $endOfLine instanceof \Closure
            ? $endOfLine
            : fn() => strval($endOfLine);

        return sprintf(
            "{%s%s}%s",
            $endOfLine(null),
            implode(
                '',
                array_map(
                    function (Declaration $declaration) use ($styleSheet, $indent, $endOfLine) {
                        return sprintf(
                            '%s%s%s',
                            $indent($declaration),
                            $declaration->render($styleSheet),
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
