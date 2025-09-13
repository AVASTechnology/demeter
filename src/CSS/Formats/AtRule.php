<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRule as AtRuleComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier;
use AVASTech\Demeter\CSS\Components\Interfaces\Comment as CommentComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\RuleSet as RuleSetComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Statement;
use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;

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
     * @var AtRuleFormat|null
     */
    public ?AtRuleFormat $atRuleFormat {
        get {
            return ($this->atRuleFormat = $this->atRuleFormat ?? $this);
        }
        set {
            $this->atRuleFormat = $value;
        }
    }

    /**
     * @var RuleSetFormat|null
     */
    public ?RuleSetFormat $ruleSetFormat {
        get {
            return ($this->ruleSetFormat = $this->ruleSetFormat ?? null);
        }
        set {
            $this->ruleSetFormat = $value;
        }
    }

    /**
     * @var CommentFormat|null
     */
    public ?CommentFormat $commentFormat {
        get {
            return ($this->commentFormat = $this->commentFormat ?? null);
        }
        set {
            $this->commentFormat = $value;
        }
    }

    /**
     * @inheritDoc
     */
    public function format(AtRuleIdentifier $identifier, string $rule, ?array $blockStatements, int $nestLevel = 0): string
    {
        $this->initializeFormats();

        if ($identifier->isBlock()) {
            $onNewStatement = $this->onNewStatement instanceof \Closure
                ? $this->onNewStatement
                : fn() => strval($this->onNewStatement);

            $endOfLine = $this->endOfLine instanceof \Closure
                ? $this->endOfLine
                : fn() => strval($this->endOfLine);

            $block = implode(
                '',
                array_map(
                    function (Statement $statement) use ($onNewStatement, $nestLevel) {
                        return match (true) {
                                $statement instanceof AtRuleComponent  => $statement->render($this->atRuleFormat, $nestLevel + 1),
                                $statement instanceof RuleSetComponent => $statement->render($this->ruleSetFormat, $nestLevel + 1),
                                $statement instanceof CommentComponent => $statement->render($this->commentFormat, $nestLevel + 1),
                            } . $onNewStatement($statement);
                    },
                    $blockStatements,
                )
            );

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
     * @return void
     */
    protected function initializeFormats(): void
    {
        $this->atRuleFormat->indent = $this->atRuleFormat->indent ?? $this->indent;
        $this->atRuleFormat->endOfLine = $this->atRuleFormat->endOfLine ?? $this->endOfLine;
        $this->atRuleFormat->onNewStatement = $this->atRuleFormat->onNewStatement ?? $this->onNewStatement;
        $this->atRuleFormat->selectorSpacing = $this->atRuleFormat->onNewStatement ?? $this->selectorSpacing;

        $this->ruleSetFormat->indent = $this->ruleSetFormat->indent ?? $this->indent;
        $this->ruleSetFormat->endOfLine = $this->ruleSetFormat->endOfLine ?? $this->endOfLine;
        $this->ruleSetFormat->onNewStatement = $this->ruleSetFormat->onNewStatement ?? $this->onNewStatement;
        $this->ruleSetFormat->selectorSpacing = $this->ruleSetFormat->onNewStatement ?? $this->selectorSpacing;

        $this->commentFormat->indent = $this->commentFormat->indent ?? $this->indent;
        $this->commentFormat->endOfLine = $this->commentFormat->endOfLine ?? $this->endOfLine;
        $this->commentFormat->onNewStatement = $this->commentFormat->onNewStatement ?? $this->onNewStatement;
    }
}
