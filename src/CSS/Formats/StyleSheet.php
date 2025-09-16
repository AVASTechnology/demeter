<?php

namespace AVASTech\Demeter\CSS\Formats;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRule as AtRuleComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Comment as CommentComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\RuleSet as RuleSetComponent;
use AVASTech\Demeter\CSS\Components\Interfaces\Statement;
use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

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
     * @var DeclarationFormat|null
     */
    public ?DeclarationFormat $declarationFormat {
        get {
            return ($this->declarationFormat = $this->declarationFormat ?? null);
        }
        set {
            $this->declarationFormat = $value;
        }
    }

    /**
     * @var DeclarationBlockFormat|null
     */
    public ?DeclarationBlockFormat $declarationBlockFormat {
        get {
            return ($this->declarationBlockFormat = $this->declarationBlockFormat ?? null);
        }
        set {
            $this->declarationBlockFormat = $value;
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
     * @var SelectorFormat|null
     */
    public ?SelectorFormat $selectorFormat {
        get {
            return ($this->selectorFormat = $this->selectorFormat ?? null);
        }
        set {
            $this->selectorFormat = $value;
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
        $onNewStatement = $this->onNewStatement instanceof \Closure
            ? $this->onNewStatement
            : fn() => strval($this->onNewStatement);

        return implode(
            '',
            array_map(
                function (Statement $statement) use ($onNewStatement){
                    return match (true) {
                        $statement instanceof AtRuleComponent  => $statement->render($this),
                        $statement instanceof RuleSetComponent => $statement->render($this),
                        $statement instanceof CommentComponent => $statement->render($this),
                    } . $onNewStatement($statement);
                },
                $statements,
            )
        );
    }
}
