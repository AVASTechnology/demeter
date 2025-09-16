<?php

namespace AVASTech\Demeter\CSS\Formats;

/**
 * Class Factory
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class Factory
{
    /**
     * @return Interfaces\StyleSheet
     */
    public function compactStyleSheet(): Interfaces\StyleSheet
    {
        return $this->createStyleSheet('', '', '', '');
    }

    /**
     * @param string|\Closure $indent
     * @param string|\Closure $endOfLine
     * @param string|\Closure $newStatement
     * @param string|\Closure $selectorSpacing
     * @return Interfaces\StyleSheet
     */
    public function prettyStyleSheet(
        string|\Closure $indent = '    ',
        string|\Closure $endOfLine = "\n",
        string|\Closure $newStatement = "\n",
        string|\Closure $selectorSpacing = ' ',
    ): Interfaces\StyleSheet {

        return $this->createStyleSheet($indent, $endOfLine, $newStatement, $selectorSpacing);
    }

    /**
     * @param string|\Closure $indent
     * @param string|\Closure $endOfLine
     * @param string|\Closure $newStatement
     * @param string|\Closure $selectorSpacing
     * @return Interfaces\StyleSheet
     */
    public function createStyleSheet(
        string|\Closure $indent,
        string|\Closure $endOfLine,
        string|\Closure $newStatement,
        string|\Closure $selectorSpacing
    ): Interfaces\StyleSheet {

        $styleSheet = new StyleSheet($indent, $endOfLine, $newStatement, $selectorSpacing);

        $styleSheet->atRuleFormat = $this->createAtRule();
        $styleSheet->commentFormat = $this->createComment();
        $styleSheet->declarationFormat = $this->createDeclaration();
        $styleSheet->declarationBlockFormat = $this->createDeclarationBlock();
        $styleSheet->ruleSetFormat = $this->createRuleSet();
        $styleSheet->selectorFormat = $this->createSelector();

        return $styleSheet;
    }

    /**
     * @param string|\Closure|null $indent
     * @param string|\Closure|null $endOfLine
     * @param string|\Closure|null $newStatement
     * @return Interfaces\AtRule
     */
    public function createAtRule(
        string|\Closure|null $indent = null,
        string|\Closure|null $endOfLine = null,
        string|\Closure|null $newStatement = null,
    ): Interfaces\AtRule {

        $atRule = new AtRule();

        $atRule->indent = $indent;
        $atRule->endOfLine = $endOfLine;
        $atRule->onNewStatement = $newStatement;

        return $atRule;
    }

    /**
     * @return Interfaces\RuleSet
     */
    public function createRuleSet(): Interfaces\RuleSet
    {
        return new RuleSet();
    }

    /**
     * @return Interfaces\Comment
     */
    public function createComment(): Interfaces\Comment
    {
        return new Comment();
    }

    /**
     * @param string|\Closure|null $indent
     * @param string|\Closure|null $endOfLine
     * @return DeclarationBlock
     */
    public function createDeclarationBlock(
        string|\Closure|null $indent = null,
        string|\Closure|null $endOfLine = null,
    ): Interfaces\DeclarationBlock {

        $declarationBlock = new DeclarationBlock();

        $declarationBlock->declarationFormat = $this->createDeclaration();
        $declarationBlock->indent = $indent;
        $declarationBlock->endOfLine = $endOfLine;

        return $declarationBlock;
    }

    /**
     * @return Interfaces\Selector
     */
    public function createSelector(): Interfaces\Selector
    {
        return new Selector();
    }

    /**
     * @return Interfaces\Declaration
     */
    public function createDeclaration(): Interfaces\Declaration
    {
        return new Declaration();
    }
}
