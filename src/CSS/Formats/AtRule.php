<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRule as AtRuleComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier;
use AVASTech\Demeter\CSS\Components\Interfaces\Comment as CommentComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock;
use AVASTech\Demeter\CSS\Components\Interfaces\RuleSet as RuleSetComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Statement;
use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet;

/**
 * Class AtRule
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class AtRule implements Interfaces\AtRule
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
    public function format(StyleSheet $styleSheet, AtRuleIdentifier $identifier, string $rule, array|DeclarationBlock|null $blockStatements, int $nestLevel = 0): string
    {
        $this->initializeFormats($styleSheet);

        if ($identifier->isBlock()) {
            $onNewStatement = $this->onNewStatement instanceof \Closure
                ? $this->onNewStatement
                : fn() => strval($this->onNewStatement);

            $endOfLine = $this->endOfLine instanceof \Closure
                ? $this->endOfLine
                : fn() => strval($this->endOfLine);

            if (is_array($blockStatements)) {
                $block = implode(
                    '',
                    array_map(
                        function (Statement $statement) use ($styleSheet, $onNewStatement, $nestLevel) {
                            return match (true) {
                                    $statement instanceof AtRuleComponent  => $statement->render($styleSheet, $nestLevel + 1),
                                    $statement instanceof RuleSetComponent => $statement->render($styleSheet, $nestLevel + 1),
                                    $statement instanceof CommentComponent => $statement->render($styleSheet, $nestLevel + 1),
                                } . $onNewStatement($statement);
                        },
                        $blockStatements,
                    )
                );
            } elseif ($blockStatements instanceof DeclarationBlock) {
                $block = $blockStatements->render($styleSheet);
            }

            return sprintf(
                $identifier->wrapRule() ? '%s (%s) {%s%s}' : '%s %s {%s%s}',
                $identifier->value,
                $rule,
                $endOfLine($identifier),
                $block,
            );
        }

        return sprintf(
            $identifier->wrapRule() ? '%s (%s);' : '%s %s;',
            $identifier->value,
            $rule
        );
    }

    /**
     * @param StyleSheet $styleSheet
     * @return void
     */
    protected function initializeFormats(StyleSheet $styleSheet): void
    {
        $this->indent = $this->indent ?? $styleSheet->indent;
        $this->endOfLine = $this->endOfLine ?? $styleSheet->endOfLine;
        $this->onNewStatement = $this->onNewStatement ?? $styleSheet->onNewStatement;
    }
}
