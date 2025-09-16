<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Interfaces\Statement;
use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Selector as SelectorFormat;

/**
 * Interface StyleSheet
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface StyleSheet extends StatementSet
{
    /**
     * @var AtRuleFormat|null
     */
    public ?AtRuleFormat $atRuleFormat { get; set; }

    /**
     * @var CommentFormat|null
     */
    public ?CommentFormat $commentFormat { get; set; }

    /**
     * @var DeclarationFormat|null
     */
    public ?DeclarationFormat $declarationFormat { get; set; }

    /**
     * @var DeclarationBlockFormat|null
     */
    public ?DeclarationBlockFormat $declarationBlockFormat { get; set; }

    /**
     * @var RuleSetFormat|null
     */
    public ?RuleSetFormat $ruleSetFormat { get; set; }

    /**
     * @var SelectorFormat|null
     */
    public ?SelectorFormat $selectorFormat { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $indent { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $endOfLine { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $onNewStatement { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $selectorSpacing { get; set; }

    /**
     * @param Statement[] $statements
     * @return string
     */
    public function format(array $statements): string;
}
