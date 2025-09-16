<?php

namespace AVASTech\Demeter\CSS\Components;

/**
 * Class Factory
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class Factory implements Interfaces\Factory
{
    /**
     * @param string $stylesheet
     * @return Interfaces\AtRuleIdentifier
     */
    public function createAtRuleIdentifier(string $stylesheet): Interfaces\AtRuleIdentifier
    {
        return AtRuleIdentifier::determineIdentifier($stylesheet);
    }

    /**
     * @param string $propertyString
     * @return Interfaces\Property
     */
    public function createProperty(string $propertyString): Interfaces\Property
    {
        return Property::from($propertyString);
    }

    /**
     * @param string $propertyString
     * @return Interfaces\Property
     */
    public function createCustomProperty(string $propertyString): Interfaces\Property
    {
        return new CustomProperty($propertyString);
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param string|null $rule
     * @param array|Interfaces\DeclarationBlock|null $blockStatements
     * @return AtRule
     */
    public function createAtRule(Interfaces\AtRuleIdentifier $identifier, ?string $rule, array|Interfaces\DeclarationBlock|null $blockStatements = null): Interfaces\AtRule
    {
        return new AtRule($identifier, $rule, $blockStatements);
    }

    /**
     * @param string $text
     * @return Interfaces\Comment
     */
    public function createComment(string $text): Interfaces\Comment
    {
        return new Comment($text);
    }

    /**
     * @param string $selector
     * @return Interfaces\Selector
     */
    public function createSelector(string $selector): Interfaces\Selector
    {
        return new Selector($selector);
    }

    /**
     * @param array $statements
     * @return Interfaces\DeclarationBlock
     */
    public function createDeclarationBlock(array $statements): Interfaces\DeclarationBlock
    {
        return new DeclarationBlock($statements);
    }

    /**
     * @param Interfaces\Property $property
     * @param string $value
     * @return Interfaces\Declaration
     */
    public function createDeclaration(Interfaces\Property $property, string $value): Interfaces\Declaration
    {
        return new Declaration($property, $value);
    }

    /**
     * @param array $selectors
     * @param Interfaces\DeclarationBlock $declarationBlock
     * @return Interfaces\RuleSet
     */
    public function createRuleSet(array $selectors, Interfaces\DeclarationBlock $declarationBlock): Interfaces\RuleSet
    {
        return new RuleSet($selectors, $declarationBlock);
    }

}
