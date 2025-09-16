<?php

namespace AVASTech\Demeter\CSS;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRule;
use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleIdentifier;
use AVASTech\Demeter\CSS\Components\Interfaces\Comment;
use AVASTech\Demeter\CSS\Components\Interfaces\DeclarationBlock;
use AVASTech\Demeter\CSS\Components\Interfaces\Factory;
use AVASTech\Demeter\CSS\Components\Interfaces\RuleSet;
use AVASTech\Demeter\CSS\Components\StyleSheet;

/**
 * Class Parser
 *
 * @package AVASTech\Demeter\CSS
 */
class Parser
{
    /**
     *
     */
    const array TOKEN_PREFIXES = [
        'comments'    => '|c:',
        'quoted'      => '|q:',
        'bracket:{:}' => '|s:',
        'bracket:(:)' => '|p:',
    ];

    /**
     * @var Factory $componentFactory
     */
    public Factory $componentFactory;

    /**
     * @var array $blocks
     */
    protected array $blocks = [];

    /**
     * @param Factory|null $componentFactory
     */
    public function __construct(?Factory $componentFactory = null)
    {
        $this->componentFactory = $componentFactory ?? new Components\Factory();
    }

    /**
     * @param string $stylesheet
     * @return StyleSheet
     */
    public function parse(string $stylesheet): StyleSheet
    {
        $stylesheet = $this->removeCodeBlocks($stylesheet);

        $statements = $this->extractStatements($stylesheet);

        return new StyleSheet($statements);
    }

    /**
     * @param string $stylesheet
     * @return string
     */
    protected function removeCodeBlocks(string $stylesheet): string
    {
        // remove whitespace
        $stylesheet = trim($stylesheet);

        // replace quoted blocks
        $stylesheet = $this->removeComments($stylesheet);

        // replace quoted blocks
        $stylesheet = $this->removeQuotedSubstrings($stylesheet);

        // replace {} blocks
        $stylesheet = $this->removeBracketedSubstrings($stylesheet, '{', '}');

        // replace () blocks
        return $this->removeBracketedSubstrings($stylesheet, '(', ')');
    }

    /**
     * @param string $stylesheet
     * @return array
     */
    protected function extractStatements(string &$stylesheet): array
    {
        $statements = [];

        $count = 0;

        while (!empty($stylesheet)) {
            switch ($stylesheet[0]) {
                case '@':
                    $statements[] = $this->extractAtRule($stylesheet);
                    break;
                case '|':
                    // replaced block - likely comment
                    $comment = $this->extractComment($stylesheet);

                    if (isset($comment)) {
                        $statements[] = $comment;
                        break;
                    }

                    // what could it be?
                    break;
                default:
                    $ruleSet = $this->extractRuleSet($stylesheet);

                    if (isset($ruleSet)) {
                        $statements[] = $ruleSet;
                    }
                    break;
            }

            $stylesheet = ltrim($stylesheet);

            if ($count++ > 2500) {
                break;
            }
        }

        return $statements;
    }

    /**
     * @param string $stylesheet
     * @return string|null
     */
    public function extractStartingHash(string &$stylesheet): ?string
    {
        if (empty($stylesheet) || $stylesheet[0] !== '|') {
            return null;
        }

        $hash = substr($stylesheet, 0, strpos($stylesheet, '|', 1) + 1);

        $stylesheet = ltrim(substr($stylesheet, strlen($hash)));

        return $hash;
    }

    /**
     * @param string $stylesheet
     * @return Comment|null
     */
    protected function extractComment(string &$stylesheet): ?Comment
    {
        $hash = $this->extractStartingHash($stylesheet);

        if (!isset($hash)) {
            return null;
        }

        $text = $this->restoreComments($hash);

        if ($text !== $hash) {
            return $this->componentFactory->createComment(
                substr($text, 2, -2)
            );
        }

        // Restore hash to the stylesheet
        $stylesheet = $hash . $stylesheet;

        return null;
    }

    /**
     * @param string $stylesheet
     * @return AtRule
     */
    protected function extractAtRule(string &$stylesheet): AtRule
    {
        $atRuleIdentifier = $this->componentFactory->createAtRuleIdentifier($stylesheet);

        if ($atRuleIdentifier->isBlock()) {
            return $this->extractAtRuleBlock($atRuleIdentifier, $stylesheet);
        }

        if ($atRuleIdentifier->isStatement()) {
            return $this->extractAtRuleStatement($atRuleIdentifier, $stylesheet);
        }

        throw new \UnexpectedValueException(
            sprintf(
                'The at-rule identifier (%s) does not have an identified style (statement or block).',
                $atRuleIdentifier->name
            )
        );
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param string $stylesheet
     * @return AtRule
     */
    protected function extractAtRuleStatement(AtRuleIdentifier $identifier, string &$stylesheet): AtRule
    {
        [$rule, $stylesheet] = explode(';', $stylesheet, 2);

        // remove identifier
        $rule = ltrim(substr($rule, strlen($identifier->value)));

        if ($rule[0] === '|' && $rule[-1] === '|') {
            $rule = $this->restoreBlock($rule);
        }

        return $this->componentFactory->createAtRule($identifier, $rule);
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param string $stylesheet
     * @return AtRule
     */
    protected function extractAtRuleBlock(AtRuleIdentifier $identifier, string &$stylesheet): AtRule
    {
        // remove identifier
        $stylesheet = ltrim(substr($stylesheet, strlen($identifier->value)));

        $rule = substr($stylesheet, 0, strpos($stylesheet, static::TOKEN_PREFIXES['bracket:{:}']));

        $stylesheet = substr($stylesheet, strlen($rule));

        if ($identifier->nestsStatements()) {
            $hash = $this->extractStartingHash($stylesheet);

            $statementBlock = $this->restoreBracketedSubstrings($hash, '{', '}');

            $rule = $this->restoreBlock(trim($rule));

            $statementBlock = substr($statementBlock, 1, -1);

            $statements = $this->extractStatements($statementBlock);
        } else {
            $statements = $this->extractDeclarationBlock($stylesheet);
        }

        return $this->componentFactory->createAtRule($identifier, trim($rule), $statements);
    }

    /**
     * @param string $stylesheet
     * @return RuleSet|null
     */
    protected function extractRuleSet(string &$stylesheet): ?RuleSet
    {
        $selectorString = substr($stylesheet, 0, strpos($stylesheet, static::TOKEN_PREFIXES['bracket:{:}']));
        $stylesheet = substr($stylesheet, strlen($selectorString));

        $selectors = array_map(
            function ($value) {
                // check for hashes
                return $this->componentFactory->createSelector(
                    $this->restoreBlock(trim($value))
                );
            },
            explode(',', $selectorString)
        );

        $declarationBlock = $this->extractDeclarationBlock($stylesheet);

        return $this->componentFactory->createRuleSet($selectors, $declarationBlock);
    }

    /**
     * @param string $stylesheet
     * @return DeclarationBlock
     */
    protected function extractDeclarationBlock(string &$stylesheet): DeclarationBlock
    {
        $hash = $this->extractStartingHash($stylesheet);

        $declarationBlockText = $this->restoreBracketedSubstrings($hash, '{', '}');

        $declarationBlockParts = explode(';', substr($declarationBlockText, 1, -1));

        $declarations = [];

        foreach ($declarationBlockParts as $declarationBlockPart) {
            $declarationBlockPart = trim($declarationBlockPart);

            if (empty($declarationBlockPart)) {
                continue;
            }

            [$propertyString, $value] = explode(':', $declarationBlockPart, 2);

            try {
                $property = $this->componentFactory->createProperty(trim($propertyString));
            } catch (\ValueError $error) {
                $property = $this->componentFactory->createCustomProperty(trim($propertyString));
            }

            $declarationValue = $this->restoreBlock(trim($value));

            $declarations[] = $this->componentFactory->createDeclaration($property, $declarationValue);
        }

        return $this->componentFactory->createDeclarationBlock($declarations);
    }

    /**
     * @param string $value
     * @param array $applicableBlocks
     * @return string
     */
    public function restoreBlock(string $value, array $applicableBlocks = ['quoted', 'bracket:{:}', 'bracket:(:)', 'comments']): string
    {
        $containsBlocks = function (string $value) {
            return !is_null(
                array_find(static::TOKEN_PREFIXES, fn($prefix) => str_contains($value, $prefix))
            );
        };

        if ($containsBlocks($value)) {
            do {
                $preDecode = $value;

                foreach ($applicableBlocks as $applicableBlock) {
                    $value = match ($applicableBlock) {
                        'quoted' => $this->restoreQuotedSubstrings($value),
                        'bracket:{:}' => $this->restoreBracketedSubstrings($value, '{', '}'),
                        'bracket:(:)' => $this->restoreBracketedSubstrings($value, '(', ')'),
                        'comments' => $this->restoreComments($value),
                        default => throw new \InvalidArgumentException('Invalid block type: ' . $applicableBlock),
                    };
                }
            } while ($containsBlocks($value) && $preDecode !== $value);
        }

        return $value;
    }

    /**
     * @param  string  $value
     * @return string
     */
    public function removeComments(string $value): string
    {
        return $this->removeEmbeddedTokens(
            $value,
            '~/\*[^*]*\*+([^/*][^*]*\*+)*\/~',
            'comments'
        );
    }

    /**
     * @param  string  $value
     * @return string
     */
    public function restoreComments(string $value): string
    {
        return $this->restoreEmbeddedTokens($value, $this->blocks['comments'] ?? []);
    }

    /**
     * @param  string  $value
     * @return string
     */
    public function removeQuotedSubstrings(string $value): string
    {
        return $this->removeEmbeddedTokens(
            $value,
            '/((?<![\\\\])[\'"])((?:(?!(?<![\\\\])\1).)*.?)\1/s',
            'quoted'
        );
    }

    /**
     * @param  string  $value
     * @return array
     */
    public function restoreQuotedSubstrings(string $value): string
    {
        return $this->restoreEmbeddedTokens($value, $this->blocks['quoted'] ?? []);
    }

    /**
     * @param  string  $value
     * @param  string  $open
     * @param  string  $close
     * @return string
     */
    public function removeBracketedSubstrings(string $value, string $open = '[', string $close = ']'): string
    {
        return $this->removeEmbeddedTokens(
            $value,
            sprintf(
                '/((?<![\\\\])[\\%s])((?:.(?!(?<![\\\\])[\\%s\\%s]))*.?)[\\%s]/s',
                $open,
                $open,
                $close,
                $close
            ),
            'bracket:' . $open . ':' . $close
        );
    }

    /**
     * @param string $value
     * @param string $open
     * @param string $close
     * @param bool $recursive
     * @return array
     */
    public function restoreBracketedSubstrings(string $value, string $open = '[', string $close = ']', bool $recursive = false): string
    {
        return $this->restoreEmbeddedTokens(
            $value,
            $this->blocks['bracket:' . $open . ':' . $close] ?? [],
            $recursive
        );
    }

    /**
     * @param string $value
     * @param string $pattern
     * @param string $tokenName
     * @return string
     */
    protected function removeEmbeddedTokens(string $value, string $pattern, string $tokenName): string
    {
        $prefix = static::TOKEN_PREFIXES[$tokenName];
        $tokens = [];
        $found  = -1;

        while (count($tokens) !== $found) {
            $found = count($tokens);
            $value = preg_replace_callback(
                $pattern,
                function ($match) use (&$tokens, $prefix) {
                    // The PREG_OFFSET_CAPTURE includes the position in match; make sure each hash is unique
                    $replacement = $prefix . sha1(implode(':', $match[0])). '|';
                    $tokens[$replacement] = $match[0][0];
                    return $replacement;
                },
                $value,
                -1,
                $count,
                PREG_OFFSET_CAPTURE
            );
        }

        $this->blocks[$tokenName] = $tokens;

        return $value;
    }

    /**
     * @param  string  $value
     * @param  array  $tokens
     * @param  bool  $recursive
     * @return string
     */
    protected function restoreEmbeddedTokens(string $value, array $tokens, bool $recursive = false): string
    {
        if ($recursive) {
            // Reversing will make the first pass nearly always work
            $tokens = array_reverse($tokens, true);
        }

        // This way at least 1 set of tokens will be replaced
        do {
            $tokenCount = count($tokens);

            foreach ($tokens as $hash => $content) {
                if (str_contains($value, $hash)) {
                    $value = str_replace(
                        $hash,
                        $content,
                        $value
                    );
                    unset($tokens[$hash]);
                }
            }
        } while ($tokenCount != count($tokens) && $recursive);

        return $value;
    }

}
