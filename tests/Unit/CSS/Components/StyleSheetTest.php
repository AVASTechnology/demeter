<?php

namespace AVASTech\Demeter\Tests\Unit\CSS\Components;

use AVASTech\Demeter\CSS\Components\StyleSheet;
use AVASTech\Demeter\Tests\Unit\UnitTestCase;
use Avastechnology\Iolaus\Traits\InvokeMethod;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class StyleSheetTest
 *
 * @package AVASTech\Demeter\Tests\Unit\CSS\Components
 */
class StyleSheetTest extends UnitTestCase
{
    use InvokeMethod;

    /**
     * @param array $primaryMapping
     * @param array $alternativeMapping
     * @param string $value
     * @param bool $recursive
     * @param string $expected
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     * @throws \ReflectionException
     */
    #[DataProvider('provider_replaceCustomProperty')]
    public function test_replaceCustomProperty(array $primaryMapping, array $alternativeMapping, string $value, bool $recursive, string $expected)
    {
        $stylesheet = $this->createPartialMock(StyleSheet::class, []);

        $this->assertEquals(
            $expected,
            $this->invokeMethod($stylesheet, 'replaceCustomProperty', [$primaryMapping, $alternativeMapping, $value, $recursive])
        );
    }

    /**
     * @return array
     */
    public static function provider_replaceCustomProperty(): array
    {
/*        return [
            [
                'primaryMapping' => [
                    '--primary-color' => 'var( --brand-a )',
                    '--brand-a' => '#FF00AA',
                    '--brand-b' => '#FF00BB',
                ],
                'alternativeMapping' => ['--alternative-color' => '#ffffff'],
                'value' => 'var(--primary-color)',
                'recursive' => true,
                'expected' => '#FF00AA',
            ]
        ];*/


        $providerData = [];

        foreach ([true, false] as $recursive) {
            $providerData[] = [
                'primaryMapping' => ['--primary-color' => '#000000'],
                'alternativeMapping' => ['--alternative-color' => '#ffffff'],
                'value' => 'var(--primary-color)',
                'recursive' => $recursive,
                'expected' => '#000000',
            ];

            $providerData[] = [
                'primaryMapping' => ['--primary-color' => '#000000'],
                'alternativeMapping' => ['--alternative-color' => '#ffffff'],
                'value' => 'var(--alternative-color)',
                'recursive' => $recursive,
                'expected' => '#ffffff',
            ];

            $providerData[] = [
                'primaryMapping' => ['--primary-color' => '#000000'],
                'alternativeMapping' => ['--alternative-color' => '#ffffff'],
                'value' => 'var(--missing-color, red)',
                'recursive' => $recursive,
                'expected' => 'red',
            ];
        }

        $providerData[] = [
            'primaryMapping' => [
                '--primary-color' => 'var( --brand-a )',
                '--brand-a' => '#FF00AA',
                '--brand-b' => '#FF00BB',
            ],
            'alternativeMapping' => ['--alternative-color' => '#ffffff'],
            'value' => 'var(--primary-color)',
            'recursive' => true,
            'expected' => '#FF00AA',
        ];

        $providerData[] = [
            'primaryMapping' => [
                '--primary-color' => 'var( --brand-c, blue)',
                '--brand-a' => '#FF00AA',
                '--brand-b' => '#FF00BB',
            ],
            'alternativeMapping' => ['--alternative-color' => '#ffffff'],
            'value' => 'var(--primary-color)',
            'recursive' => true,
            'expected' => 'blue',
        ];

        $providerData[] = [
            'primaryMapping' => [
                '--primary-color' => 'var( --brand-a )',
                '--brand-a' => '#FF00AA',
                '--brand-b' => '#FF00BB',
            ],
            'alternativeMapping' => ['--alternative-color' => '#ffffff'],
            'value' => 'var(--primary-color)',
            'recursive' => false,
            'expected' => 'var( --brand-a )',
        ];

        $providerData[] = [
            'primaryMapping' => [
                '--shadow-x' => '1',
                '--shadow-y' => '2',
                '--shadow-blur' => '3',
                '--shadow-width' => '4',
                '--color-a' => '#AA0000',
                '--color-b' => '#BB0000',
                '--color-c' => '#CC0000'
            ],
            'alternativeMapping' => ['--alternative-color' => '#ffffff'],
            'value' => 'box-shadow: var(--shadow-x, 0) var(--shadow-y, 0) var(--shadow-blur, 0) var(--shadow-width) var(--color-a)',
            'recursive' => true,
            'expected' => 'box-shadow: 1 2 3 4 #AA0000',
        ];

        $providerData[] = [
            'primaryMapping' => [
                '--shadow-x' => '1',
                '--shadow-y' => '2',
                '--shadow-width' => '4',
                '--color-a' => '#AA0000',
                '--color-b' => '#BB0000',
                '--color-c' => '#CC0000'
            ],
            'alternativeMapping' => ['--alternative-color' => '#ffffff'],
            'value' => 'box-shadow: var(--shadow-x, 0) var(--shadow-y, 0) var(--shadow-blur, 0) var(--shadow-width) var(--color-a)',
            'recursive' => true,
            'expected' => 'box-shadow: 1 2 0 4 #AA0000',
        ];

        return $providerData;
    }
}
