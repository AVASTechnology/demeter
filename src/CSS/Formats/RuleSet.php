<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock as DeclarationBlockInterface;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector as SelectorInterface;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class RuleSet
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class RuleSet implements Interfaces\RuleSet
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
     * @var string|\Closure|null
     */
    public string|\Closure|null $selectorSpacing {
        get {
            return $this->selectorSpacing ?? null;
        }
        set {
            $this->selectorSpacing = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function format(StyleSheet $styleSheet, array $selectors, DeclarationBlockInterface $declarationBlock, int $nestLevel = 0): string
    {
        $selectorSpacing = $this->selectorSpacing ?? $styleSheet->selectorSpacing;
        $selectorSpacing = $selectorSpacing instanceof \Closure
            ? ($selectorSpacing)($selectors)
            : strval($selectorSpacing);

        $selectors = array_map(fn(SelectorInterface $selector) => $selector->render($styleSheet), $selectors);

        return sprintf(
            '%s %s',
            implode(sprintf(',%s', $selectorSpacing), $selectors),
            $declarationBlock->render($styleSheet)
        );
    }
}
