<?php

namespace AVASTech\Demeter\CSS\Components\Interfaces;

/**
 * Interface Factory
 *
 * @package AVASTech\Demeter\CSS\Components\Interfaces
 */
interface Factory
{
    /**
     * @param string $stylesheet
     * @return AtRuleIdentifier
     */
    public function createAtRuleIdentifier(string $stylesheet): AtRuleIdentifier;

    /**
     * @param string $propertyString
     * @return Property
     */
    public function createProperty(string $propertyString): Property;

    /**
     * @param string $propertyString
     * @return Property
     */
    public function createCustomProperty(string $propertyString): Property;

    /**
     * @param AtRuleIdentifier $identifier
     * @param string|null $rule
     * @param $block
     * @return AtRule
     */
    public function createAtRule(AtRuleIdentifier $identifier, ?string $rule, ?array $blockStatements = null): AtRule;

    /**
     * @param string $text
     * @return Comment
     */
    public function createComment(string $text): Comment;

    /**
     * @param string $selector
     * @return Selector
     */
    public function createSelector(string $selector): Selector;

    /**
     * @param array $statements
     * @return DeclarationBlock
     */
    public function createDeclarationBlock(array $statements): DeclarationBlock;

    /**
     * @param Property $property
     * @param string $value
     * @return Declaration
     */
    public function createDeclaration(Property $property, string $value): Declaration;

    /**
     * @param array $selectors
     * @param DeclarationBlock $declarationBlock
     * @return RuleSet
     */
    public function createRuleSet(array $selectors, DeclarationBlock $declarationBlock): RuleSet;
}
