<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Formats\Interfaces\AtRule as AtRuleFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\Comment as CommentFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\RuleSet as RuleSetFormat;

/**
 * Interface StatementSet
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface StatementSet
{
    /**
     * @var AtRuleFormat|null
     */
    public ?AtRuleFormat $atRuleFormat { get; set; }

    /**
     * @var RuleSetFormat|null
     */
    public ?RuleSetFormat $ruleSetFormat { get; set; }

    /**
     * @var CommentFormat|null
     */
    public ?CommentFormat $commentFormat { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $onNewStatement { get; set; }
}
