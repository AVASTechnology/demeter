<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRule as AtRuleComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Comment as CommentComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\RuleSet as RuleSetComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Statement;
use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;

/**
 * Class StyleSheet
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class StyleSheet implements Interfaces\StyleSheet
{
    /**
     * @var AtRuleFormat|null
     */
    public ?AtRuleFormat $atRuleFormat {
        get {
            return ($this->atRuleFormat = $this->atRuleFormat ?? null);
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
     * @param string|\Closure|null $indent
     * @param string|\Closure|null $endOfLine
     * @param string|\Closure|null $onNewStatement
     * @param string|\Closure|null $selectorSpacing
     */
    public function __construct(
        public string|\Closure|null $indent = '    ',
        public string|\Closure|null $endOfLine = "\n",
        public string|\Closure|null $onNewStatement = "\n",
        public string|\Closure|null $selectorSpacing = ' ',
    ) {
        //
    }

    /**
     * @param Statement[] $statements
     * @return string
     */
    public function format(array $statements): string
    {
        $this->initializeFormats();

        $onNewStatement = $this->onNewStatement instanceof \Closure
            ? $this->onNewStatement
            : fn() => strval($this->onNewStatement);

        return implode(
            '',
            array_map(
                function (Statement $statement) use ($onNewStatement){
                    return match (true) {
                        $statement instanceof AtRuleComponent  => $statement->render($this->atRuleFormat),
                        $statement instanceof RuleSetComponent => $statement->render($this->ruleSetFormat),
                        $statement instanceof CommentComponent => $statement->render($this->commentFormat),
                    } . $onNewStatement($statement);
                },
                $statements,
            )
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

        $this->atRuleFormat->ruleSetFormat = $this->atRuleFormat->ruleSetFormat ?? $this->ruleSetFormat;
        $this->atRuleFormat->commentFormat = $this->atRuleFormat->commentFormat ?? $this->commentFormat;

        $this->ruleSetFormat->indent = $this->ruleSetFormat->indent ?? $this->indent;
        $this->ruleSetFormat->endOfLine = $this->ruleSetFormat->endOfLine ?? $this->endOfLine;
        $this->ruleSetFormat->onNewStatement = $this->ruleSetFormat->onNewStatement ?? $this->onNewStatement;
        $this->ruleSetFormat->selectorSpacing = $this->ruleSetFormat->onNewStatement ?? $this->selectorSpacing;

        $this->commentFormat->indent = $this->commentFormat->indent ?? $this->indent;
        $this->commentFormat->endOfLine = $this->commentFormat->endOfLine ?? $this->endOfLine;
        $this->commentFormat->onNewStatement = $this->commentFormat->onNewStatement ?? $this->onNewStatement;
    }
}
