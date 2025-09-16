<?php

namespace AVASTech\Demeter\Tests\Functional\CSS;

use AVASTech\Demeter\CSS\Components\AtRule;
use AVASTech\Demeter\CSS\Components\AtRuleIdentifier;
use AVASTech\Demeter\CSS\Components\DeclarationBlock;
use AVASTech\Demeter\CSS\Components\Factory;
use AVASTech\Demeter\CSS\Components\Interfaces\Selector;
use AVASTech\Demeter\CSS\Components\RuleSet;
use AVASTech\Demeter\CSS\Parser;
use AVASTech\Demeter\Tests\Functional\FunctionalTestCase;
use Avastechnology\Iolaus\Traits\InvokeMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

/**
 * Class ParserTest
 *
 * @package AVASTech\Demeter\Tests\Functional\CSS
 */
class ParserTest extends FunctionalTestCase
{
    use InvokeMethod;

    /**
     * @return void
     */
    public function test_parse()
    {
        $parser = new Parser();

        $styleSheet = $parser->parse(file_get_contents(__DIR__ . '/../../bootstrap.css'));

        $this->assertInstanceOf(StyleSheet::class, $styleSheet);
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param string $stylesheet
     * @param string|null $expectedRule
     * @param array|null $expectedStatements
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provider_extractAtRuleBlock')]
    public function test_extractAtRuleBlock(
        AtRuleIdentifier $identifier,
        string $stylesheet,
        ?string $expectedRule,
        array|string|null $expectedStatements
    ) {
        $mockFactory = $this->createPartialMock(Factory::class, ['createAtRule']);
        $mockAtRule = $this->createMock(AtRule::class);

        $mockFactory->expects($this->once())
            ->method('createAtRule')
            ->willReturnCallback(
                function (AtRuleIdentifier $submittedIdentifier, string $submittedRule, array|DeclarationBlock|null $submittedStatements) use ($mockAtRule, $identifier, $expectedRule, $expectedStatements) {
                    $this->assertSame($identifier, $submittedIdentifier);
                    $this->assertSame($expectedRule, $submittedRule);

                    if (is_array($expectedStatements)) {
                        $this->assertEquals(
                            count($expectedStatements),
                            is_array($submittedStatements) ? count($submittedStatements) : $submittedStatements,
                        );
                    } elseif (is_string($expectedStatements)) {
                        $this->assertInstanceOf(
                            $expectedStatements,
                            $submittedStatements,
                        );
                    } else {
                        $this->assertNull($submittedStatements);
                    }

                    return $mockAtRule;
                }
            );

        $parser = new Parser($mockFactory);

        $encodedStylesheet = $this->invokeMethod($parser, 'removeCodeBlocks', [$stylesheet]);

        $result = $this->invokeMethod($parser, 'extractAtRuleBlock', [$identifier, $encodedStylesheet]);

        $this->assertSame($mockAtRule, $result);
    }

    /**
     * @return array[]
     */
    public static function provider_extractAtRuleBlock(): array
    {
        return [

            [
                'identifier' => AtRuleIdentifier::MEDIA,
                'stylesheet' => '@media (prefers-reduced-motion: reduce) {
                    .progress-bar {
                        transition: none;
                    }
                }
                div { display:none; }',
                'expectedRule' => '(prefers-reduced-motion: reduce)',
                'expectedStatements' => [
                    '.progress-bar',
                ],
            ],
            [
                'identifier' => AtRuleIdentifier::KEYFRAMES,
                'stylesheet' => '@keyframes spinner-border {
                    to {
                        transform: rotate(360deg) /* rtl:ignore */;
                    }
                }
                div { display:none; }',
                'expectedRule' => 'spinner-border',
                'expectedStatements' => [
                    'to',
                ],
            ],
            [
                'identifier' => AtRuleIdentifier::FONT_FACE,
                'stylesheet' => '@font-face {
                    font-display: block;
                    font-family: "MyFont";
                    src: url("./example/myFont") format("woff2"),
                }
                div { display:none; }',
                'expectedRule' => '',
                'expectedStatements' => DeclarationBlock::class,
            ],
        ];
    }

    /**
     * @param string $stylesheet
     * @param array $expectedSelectors
     * @param array $expectedDeclarationBlock
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provider_extractRuleSet')]
    public function test_extractRuleSet(string $stylesheet, array $expectedSelectors, array $expectedDeclarationBlock)
    {
        $mockFactory = $this->createPartialMock(Factory::class, ['createRuleSet']);

        $mockRuleSet = $this->createMock(RuleSet::class);

        $mockFactory->expects($this->once())
            ->method('createRuleSet')
            ->willReturnCallback(
                function (array $selectors, $declarationBlock) use ($mockRuleSet, $expectedSelectors, $expectedDeclarationBlock) {
                    $this->assertEquals(
                        $expectedSelectors,
                        array_map(fn(Selector $selector) => $selector->selector, $selectors)
                    );

                    $this->assertEquals(
                        $expectedDeclarationBlock,
                        $declarationBlock->toDictionary()
                    );

                    return $mockRuleSet;
                }
            );

        $parser = new Parser($mockFactory);

        $encodedStylesheet = $this->invokeMethod($parser, 'removeCodeBlocks', [$stylesheet]);

        $result = $this->invokeMethod($parser, 'extractRuleSet', [$encodedStylesheet]);

        $this->assertSame($mockRuleSet, $result);
    }

    /**
     * @return array
     */
    public static function provider_extractRuleSet(): array
    {
        return [
            [
                'stylesheet' => 'hr {
                    margin: 1rem 0;
                    color: inherit;
                    border: 0;
                    border-top: var(--bs-border-width) solid;
                    opacity: 0.25;
                }',
                'expectedSelectors' => [
                    'hr',
                ],
                'expectedDeclarationBlock' => [
                    'margin' => '1rem 0',
                    'color' => 'inherit',
                    'border' => '0',
                    'border-top' => 'var(--bs-border-width) solid',
                    'opacity' => '0.25',
                ],
            ],
            [
                'stylesheet' => '.special {
                    font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial;
                    color: var(--bs-text-color);
                }',
                'expectedSelectors' => [
                    '.special',
                ],
                'expectedDeclarationBlock' => [
                    'color' => 'var(--bs-text-color)',
                    'font-family' => 'system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", "Noto Sans", "Liberation Sans", Arial',
                ],
            ],
            [
                'stylesheet' => '.input-group > .form-floating:not(:first-child) > .form-control,
                .input-group > .form-floating:not(:first-child) > .form-select {
                    border-top-left-radius: 0;
                    border-bottom-left-radius: 0;
                }',
                'expectedSelectors' => [
                    '.input-group > .form-floating:not(:first-child) > .form-control',
                    '.input-group > .form-floating:not(:first-child) > .form-select',
                ],
                'expectedDeclarationBlock' => [
                    'border-top-left-radius' => '0',
                    'border-bottom-left-radius' => '0',
                ],
            ],
            [
                'stylesheet' => '.form-select[multiple],
                    .form-select[size]:not([size="1"]) {
                        background-image: none;
                        padding-right: 0.75rem;
                }',
                'expectedSelectors' => [
                    '.form-select[multiple]',
                    '.form-select[size]:not([size="1"])',
                ],
                'expectedDeclarationBlock' => [
                    'background-image' => 'none',
                    'padding-right' => '0.75rem',
                ],
            ],
            [
                'stylesheet' => '.was-validated .form-select:valid:not([multiple]):not([size]), .was-validated .form-select:valid:not([multiple])[size="1"], .form-select.is-valid:not([multiple]):not([size]), .form-select.is-valid:not([multiple])[size="1"] {
                    padding-right: 4.125rem;
                    background-position: right 0.75rem center, center right 2.25rem;
                    background-size: 16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
                }',
                'expectedSelectors' => [
                    '.was-validated .form-select:valid:not([multiple]):not([size])',
                    '.was-validated .form-select:valid:not([multiple])[size="1"]',
                    '.form-select.is-valid:not([multiple]):not([size])',
                    '.form-select.is-valid:not([multiple])[size="1"]',
                ],
                'expectedDeclarationBlock' => [
                    'padding-right' => '4.125rem',
                    'background-position' => 'right 0.75rem center, center right 2.25rem',
                    'background-size' => '16px 12px, calc(0.75em + 0.375rem) calc(0.75em + 0.375rem)',
                ],
            ],
            [
                'stylesheet' => '.breadcrumb-item + .breadcrumb-item::before {
                    float: left;
                    padding-right: var(--bs-breadcrumb-item-padding-x);
                    color: var(--bs-breadcrumb-divider-color);
                    content: var(--bs-breadcrumb-divider, "/") /* rtl: var(--bs-breadcrumb-divider, "/") */;
                }',
                'expectedSelectors' => [
                    '.breadcrumb-item + .breadcrumb-item::before',
                ],
                'expectedDeclarationBlock' => [
                    'float' => 'left',
                    'padding-right' => 'var(--bs-breadcrumb-item-padding-x)',
                    'color' => 'var(--bs-breadcrumb-divider-color)',
                    'content' => 'var(--bs-breadcrumb-divider, "/") /* rtl: var(--bs-breadcrumb-divider, "/") */',
                ],
            ],
        ];
    }

    /**
     * @param string $original
     * @param string $expected
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provider_restoreBlock')]
    public function test_restoreBlock(string $text): void
    {
        $mockFactory = $this->createMock(Factory::class);

        $parser = new Parser($mockFactory);

        $encoded = $this->invokeMethod($parser, 'removeCodeBlocks', [$text]);

        $this->assertFalse(str_contains($encoded, '"'));
        $this->assertFalse(str_contains($encoded, '/*'));
        $this->assertFalse(str_contains($encoded, '('));
        $this->assertFalse(str_contains($encoded, ')'));
        $this->assertFalse(str_contains($encoded, '{'));
        $this->assertFalse(str_contains($encoded, '}'));

        $this->assertEquals($text, $parser->restoreBlock($encoded));
    }

    /**
     * @return array[]
     */
    public static function provider_restoreBlock(): array
    {
        return [
            [
                'text' => 'foo',
            ],
            [
                'text' => 'foo:not[value="bar"]baz',
            ],
            [
                'text' => 'div { display:none }',
            ],
            [
                'text' => 'foo(bar)baz',
            ],
            [
                'text' => '.was-validated .form-select:valid:not([multiple])[size="1"]'
            ],
        ];
    }
}
