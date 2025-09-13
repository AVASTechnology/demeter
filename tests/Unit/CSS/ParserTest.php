<?php

namespace AVASTech\Demeter\Tests\Unit\CSS;

use AVASTech\Demeter\CSS\Parser;
use AVASTech\Demeter\Tests\Unit\UnitTestCase;
use Avastechnology\Iolaus\Traits\InvokeGetter;
use Avastechnology\Iolaus\Traits\InvokeMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;

/**
 * Class ParserTest
 *
 * @package AVASTech\Demeter\Tests\Unit\CSS
 */
class ParserTest extends UnitTestCase
{
    use InvokeGetter;
    use InvokeMethod;

    /**
     * @param string $value
     * @param array $expectedBlocks
     * @param string $expectedString
     * @return void
     * @throws \ReflectionException
     */
    #[DataProvider('provide_removeComments')]
    public function test_removeComments(string $value, array $expectedBlocks, string $expectedString)
    {
        $parser = $this->createPartialMock(Parser::class, []);

        $parsed = $this->invokeMethod($parser, 'removeComments', [$value]);

        $blocks = $this->invokeGetter($parser, 'blocks');

        $this->assertEquals(array_values($expectedBlocks), array_values($blocks['comments']));

        $this->assertEquals(
            $this->buildExpectedString($expectedString, $blocks['comments']),
            $parsed
        );
    }

    /**
     * @return array[]
     */
    public static function provide_removeComments(): array
    {
        return [
            [
                'value' => 'some general text',
                'expectedBlocks' => [],
                'expectedString' => 'some general text',
            ],
            [
                'value' => '/* comment */',
                'expectedBlocks' => [
                    '/* comment */',
                ],
                'expectedString' => '{$0}',
            ],
            [
                'value' => "some text\n/* comment */\n more\n text\n /* comment\n * with\n * new\n * line */ final text",
                'expectedBlocks' => [
                    '/* comment */',
                    "/* comment\n * with\n * new\n * line */",
                ],
                'expectedString' => sprintf(
                    "some text\n%s\n more\n text\n %s final text",
                    '{$0}',
                    '{$1}'
                ),
            ],
        ];
    }


    /**
     * @param string $value
     * @param string $open
     * @param string $close
     * @param array $expectedBlocks
     * @param string $expectedString
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provide_removeBracketedSubstrings')]
    public function test_removeBracketedSubstrings(string $value, string $open, string $close, array $expectedBlocks, string $expectedString)
    {
        $parser = $this->createPartialMock(Parser::class, []);

        $parsed = $this->invokeMethod($parser, 'removeBracketedSubstrings', [$value, $open, $close]);

        $blocks = $this->invokeGetter($parser, 'blocks');

        $blockName = 'bracket:' . $open . ':' . $close;

        $this->assertEquals($this->buildExpectedBlocks($expectedBlocks, $blocks[$blockName]), array_values($blocks[$blockName]));

        $this->assertEquals(
            $this->buildExpectedString($expectedString, $blocks[$blockName]),
            $parsed
        );
    }

    /**
     * @return array[]
     */
    public static function provide_removeBracketedSubstrings(): array
    {
        $providerData = [];

        foreach ([['[', ']'], ['(', ')'], ['{', '}']] as $brackets) {
            $providerData[] = [
                'value' => 'some general text',
                'open' => $brackets[0],
                'close' => $brackets[1],
                'expectedBlocks' => [],
                'expectedString' => 'some general text',
            ];

            $providerData[] = [
                'value' => sprintf(
                    'some %sgeneral%s text',
                    $brackets[0],
                    $brackets[1],
                ),
                'open' => $brackets[0],
                'close' => $brackets[1],
                'expectedBlocks' => [
                    $brackets[0] . 'general' . $brackets[1],
                ],
                'expectedString' => 'some {$0} text',
            ];

            $providerData[] = [
                'value' => sprintf(
                    'embedded %sbrackets %sinside%s other%s brackets',
                    $brackets[0],
                    $brackets[0],
                    $brackets[1],
                    $brackets[1],
                ),
                'open' => $brackets[0],
                'close' => $brackets[1],
                'expectedBlocks' => [
                    $brackets[0] . 'inside' . $brackets[1],
                    $brackets[0] . 'brackets {$0} other' . $brackets[1],
                ],
                'expectedString' => 'embedded {$1} brackets',
            ];

            $multipleBrackets = "text with {multiple \n blocks}\n of (multiple \n blocks) on [multiple \n blocks] of lines";

            $providerData[] = [
                'value' => $multipleBrackets,
                'open' => $brackets[0],
                'close' => $brackets[1],
                'expectedBlocks' => [
                    $brackets[0] . "multiple \n blocks" . $brackets[1],
                ],
                'expectedString' => str_replace(
                    $brackets[0] . "multiple \n blocks" . $brackets[1],
                    '{$0}',
                    $multipleBrackets
                ),
            ];
        }

        return $providerData;
    }

    /**
     * @param string $value
     * @param array $tokens
     * @param bool $recursive
     * @param string $expected
     * @return void
     * @throws Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provider_restoreEmbeddedTokens')]
    public function test_restoreEmbeddedTokens(string $value, array $tokens, bool $recursive, string $expected)
    {
        $this->assertEquals(
            $expected,
            $this->invokeMethod(
                $this->createPartialMock(Parser::class, []),
                'restoreEmbeddedTokens',
                [$value, $tokens, $recursive]
            )
        );
    }

    /**
     * @return array
     */
    public static function provider_restoreEmbeddedTokens(): array
    {
        $providerData = [];

        foreach ([true, false] as $recursive) {
            $providerData[] = [
                'value' => 'some general text',
                'tokens' => [],
                'recursive' => $recursive,
                'expected' => 'some general text',
            ];

            $providerData[] = [
                'value' => 'replaced {$1} general text',
                'tokens' => [
                    '{$1}' => 'some',
                    '{$2}' => 'other',
                ],
                'recursive' => $recursive,
                'expected' => 'replaced some general text',
            ];

            $providerData[] = [
                'value' => 'multiple {$1} replaced {$3} text',
                'tokens' => [
                    '{$1}' => 'some',
                    '{$2}' => 'other',
                    '{$3}' => 'more',
                ],
                'recursive' => $recursive,
                'expected' => 'multiple some replaced more text',
            ];
        }

        $providerData[] = [
            'value' => 'recursive false {$1} replaced {$4} text',
            'tokens' => [
                '{$1}' => 'some',
                '{$2}' => 'other',
                '{$3}' => 'more',
                '{$4}' => '{$3}',
            ],
            'recursive' => false,
            'expected' => 'recursive false some replaced {$3} text',
        ];

        $providerData[] = [
            'value' => 'recursive true {$1} replaced {$4} text',
            'tokens' => [
                '{$1}' => 'some',
                '{$2}' => 'other',
                '{$3}' => 'more',
                '{$4}' => '{$3}',
            ],
            'recursive' => true,
            'expected' => 'recursive true some replaced more text',
        ];

        return $providerData;
    }

    /**
     * @param string $expectedString
     * @param array $blocks
     * @return string
     */
    protected function buildExpectedString(string $expectedString, array $blocks): string
    {
        $hashes = array_keys($blocks);

        $mapping = array_combine(
            array_map(
                fn($key) => sprintf('{$%s}', $key),
                array_keys($hashes)
            ),
            $hashes,
        );

        return strtr($expectedString, $mapping);
    }

    /**
     * @param array $expectedBlocks
     * @param array $blocks
     * @return array
     */
    protected function buildExpectedBlocks(array $expectedBlocks, array $blocks): array
    {
        $hashes = array_keys($blocks);

        $mapping = array_combine(
            array_map(
                fn($key) => sprintf('{$%s}', $key),
                array_keys($hashes)
            ),
            $hashes,
        );

        foreach ($expectedBlocks as $index => $block) {
            $expectedBlocks[$index] = strtr($block, $mapping);
        }

        return $expectedBlocks;
    }
}
