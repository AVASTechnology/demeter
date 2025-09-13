<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Formats\Interfaces\StyleSheet as StyleSheetFormat;

/**
 * Class StyleSheet
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class StyleSheet
{
    public function __construct(protected array $statements)
    {

    }

    public function addStatement($statement): void
    {
        $this->statements[] = $statement;
    }

    /**
     * @param Interfaces\AtRuleIdentifier $identifier
     * @return void
     */
    public function removeAtRule(Interfaces\AtRuleIdentifier $identifier): void
    {
        $this->statements = array_filter(
            $this->statements,
            function ($statement) use ($identifier) {
                if (!($statement instanceof AtRule) || !$statement->identifier->isBlock()) {
                    // keep all non-at-rule statements and non-statement at-rules
                    return true;
                } elseif ($statement->identifier === $identifier) {
                    // AtRule is the identifier we're looking for, remove it'
                    return false;
                }

                // Nested AtRules
                $statement->removeAtRule($identifier);

                // Keep if at-rule still has statements
                return !empty($statement->blockStatements);
            }
        );
    }


    /**
     * @param string|null $documentSelector
     * @return void
     */
    public function removeCustomProperties(?string $documentSelector = null): void
    {
        $documentSelector = $documentSelector ?? ':root';

        $propertyMapping = [];
        $previousPropertyMapping = [];

        foreach ($this->statements as $statement) {
            switch (true) {
                case $statement instanceof Interfaces\AtRule && $statement->identifier->isBlock():

                    break;
                case $statement instanceof Interfaces\RuleSet:
                    $setPropertyMapping = $statement->matches($documentSelector);
                    $statementMapping = $propertyMapping;

                    foreach ($statement->declarationBlock->declarations as $declaration) {
                        if (str_contains($declaration->value, 'var(')) {
                            $mappedValue = $this->replaceCustomProperty($propertyMapping, $previousPropertyMapping, $declaration->value);

                            if ($declaration->value === $mappedValue || $mappedValue === '') {
                                // Remove property with invalid value
                                $statement->declarationBlock->removeDeclaration($declaration->property);
                            }

                            $declaration->value = $mappedValue;
                        }

                        if ($declaration->property instanceof CustomProperty) {
                            if ($setPropertyMapping) {
                                $propertyMapping[$declaration->property->value] = $declaration->value;
                            }

                            $statementMapping[$declaration->property->value] = $declaration->value;
                            $statement->declarationBlock->removeDeclaration($declaration->property);
                        }
                    }

                    $previousPropertyMapping = array_merge($previousPropertyMapping, $statementMapping);
                    break;
            }
        }
    }

    /**
     * @param array $primaryMapping
     * @param array $alternativeMapping
     * @param string $value
     * @param bool $recursive
     * @return string
     */
    protected function replaceCustomProperty(array $primaryMapping, array $alternativeMapping, string $value, bool $recursive = true): string
    {
        if (preg_match_all('~var\(\s*(--[a-z0-9A-Z\-]+)\s*(,([^)]*))*\)~', $value, $matches)) {
            $subProperties = false;

            foreach ($matches[0] as $index => $match) {
                $propertyName = $matches[1][$index];
                $default = trim($matches[3][$index] ?? '');

                if (isset($primaryMapping[$propertyName])) {
                    $value = str_replace($match, $primaryMapping[$propertyName], $value);
                    $subProperties = $subProperties | str_contains($primaryMapping[$propertyName], 'var(');
                } elseif (isset($alternativeMapping[$propertyName])) {
                    $value = str_replace($match, $alternativeMapping[$propertyName], $value);
                    $subProperties = $subProperties | str_contains($alternativeMapping[$propertyName], 'var(');
                } elseif ($default !== '') {
                    // Default var() value
                    $value = str_replace($match, $default, $value);
                }
            }

            if ($recursive && $subProperties && str_contains($value, 'var(')) {
                $value = $this->replaceCustomProperty($primaryMapping, $alternativeMapping, $value, $recursive);
            }
        }

        return $value;
    }

    /**
     * @param StyleSheetFormat $format
     * @return string
     */
    public function render(StyleSheetFormat $format): string
    {
        return $format->format($this->statements);
    }
}
