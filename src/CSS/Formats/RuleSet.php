<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock as DeclarationBlockInterface;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector as SelectorInterface;
use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

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
     * @var DeclarationBlockFormat|null
     */
    public ?DeclarationBlockFormat $declarationBlockFormat {
        get {
            return $this->declarationBlockFormat ?? null;
        }
        set {
            $this->declarationBlockFormat = $value;
        }
    }

    /**
     * @var SelectorFormat|null
     */
    public ?SelectorFormat $selectorFormat {
        get {
            return $this->selectorFormat ?? null;
        }
        set {
            $this->selectorFormat = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function format(array $selectors, DeclarationBlockInterface $declarationBlock, int $nestLevel = 0): string
    {
        if (!isset($this->declarationBlockFormat)) {
            throw new \UnexpectedValueException('Declaration block format not set.');
        }

        if (!isset($this->selectorFormat)) {
            throw new \UnexpectedValueException('Selector format not set.');
        }

        $this->initializeFormats();

        $selectorSpacing = $this->selectorSpacing instanceof \Closure
            ? ($this->selectorSpacing)($selectors)
            : strval($this->selectorSpacing);

        $selectors = array_map(fn(SelectorInterface $selector) => $selector->render($this->selectorFormat), $selectors);

        return sprintf(
            '%s %s',
            implode(sprintf(',%s', $selectorSpacing), $selectors),
            $declarationBlock->render($this->declarationBlockFormat)
        );
    }

    /**
     * @return void
     */
    protected function initializeFormats(): void
    {
        $this->declarationBlockFormat->indent = $this->declarationBlockFormat->indent ?? $this->indent;
        $this->declarationBlockFormat->endOfLine = $this->declarationBlockFormat->endOfLine ?? $this->endOfLine;
    }
}
