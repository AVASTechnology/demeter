<?php

namespace AVASTech\Demeter\Tests\Unit\CSS\Components;

use AVASTech\Demeter\CSS\Components\AtRuleIdentifier;
use AVASTech\Demeter\Tests\Unit\UnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Class AtRuleIdentifierTest
 *
 * @package AVASTech\Demeter\Tests\Unit\CSS\Components
 */
class AtRuleIdentifierTest extends UnitTestCase
{
    /**
     * @param string $css
     * @param AtRuleIdentifier|null $expected
     * @return void
     */
    #[DataProvider('provider_determineIdentifier')]
    public function test_determineIdentifier(string $css, ?AtRuleIdentifier $expected)
    {
        if (!isset($expected)) {
            $this->expectException(\InvalidArgumentException::class);
        }

        $this->assertEquals(
            $expected,
            AtRuleIdentifier::determineIdentifier($css)
        );
    }

    /**
     * @return array[]
     */
    public static function provider_determineIdentifier(): array
    {
        return [
            ['@charset "UTF-8";', AtRuleIdentifier::CHARSET],
            ['@import url("https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap");', AtRuleIdentifier::IMPORT],
            ['@font-face { font-family: "Roboto"; src: url("https://fonts.gstatic.com/s/roboto/v20/KFOmCnqEu92Fr1Mu4mxKKTU1Kg.woff2") format("woff2"); font-weight: 300; font-style: normal; }', AtRuleIdentifier::FONT_FACE],
            ['@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }', AtRuleIdentifier::KEYFRAMES],
            ['@media (min-width: 768px) { .container { max-width: 720px; } }', AtRuleIdentifier::MEDIA],
            ['@supports (display: grid) { .container { display: grid; } }', AtRuleIdentifier::SUPPORTS],
            ['@page { margin: 1cm; }', AtRuleIdentifier::PAGE],
            ['div.row { display: flex; }', null],
        ];
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param bool $expected
     * @return void
     */
    #[DataProvider('provider_isStatement')]
    public function test_isStatement(AtRuleIdentifier $identifier, bool $expected): void
    {
        $this->assertSame($expected, $identifier->isStatement());
    }

    /**
     * @return array[]
     */
    public static function provider_isStatement(): array
    {
        return [
            [AtRuleIdentifier::CHARSET, true],
            [AtRuleIdentifier::IMPORT, true],
            [AtRuleIdentifier::LAYER, true],
            [AtRuleIdentifier::NAMESPACE, true],
            // Non-statement rules
            [AtRuleIdentifier::COLOR_PROFILE, false],
            [AtRuleIdentifier::CONTAINER, false],
            [AtRuleIdentifier::COUNTER_STYLE, false],
            [AtRuleIdentifier::FONT_FACE, false],
            [AtRuleIdentifier::FONT_FEATURE_VALUES, false],
            [AtRuleIdentifier::FONT_PALETTE_VALUES, false],
            [AtRuleIdentifier::KEYFRAMES, false],
            [AtRuleIdentifier::MEDIA, false],
            [AtRuleIdentifier::PAGE, false],
            [AtRuleIdentifier::POSITION_TRY, false],
            [AtRuleIdentifier::PROPERTY, false],
            [AtRuleIdentifier::SCOPE, false],
            [AtRuleIdentifier::STARTING_STYLE, false],
            [AtRuleIdentifier::SUPPORTS, false],
            [AtRuleIdentifier::VIEW_TRANSITION, false],
        ];
    }

    /**
     * @param AtRuleIdentifier $identifier
     * @param bool $expected
     * @return void
     */
    #[DataProvider('provider_isBlock')]
    public function test_isBlock(AtRuleIdentifier $identifier, bool $expected): void
    {
        $this->assertSame($expected, $identifier->isBlock());
    }

    /**
     * @return array[]
     */
    public static function provider_isBlock(): array
    {
        return [
            // Block rules should return true
            [AtRuleIdentifier::COLOR_PROFILE, true],
            [AtRuleIdentifier::COUNTER_STYLE, true],
            [AtRuleIdentifier::CONTAINER, true],
            [AtRuleIdentifier::FONT_FACE, true],
            [AtRuleIdentifier::FONT_FEATURE_VALUES, true],
            [AtRuleIdentifier::FONT_PALETTE_VALUES, true],
            [AtRuleIdentifier::KEYFRAMES, true],
            [AtRuleIdentifier::MEDIA, true],
            [AtRuleIdentifier::PAGE, true],
            [AtRuleIdentifier::POSITION_TRY, true],
            [AtRuleIdentifier::PROPERTY, true],
            [AtRuleIdentifier::SCOPE, true],
            [AtRuleIdentifier::STARTING_STYLE, true],
            [AtRuleIdentifier::SUPPORTS, true],
            [AtRuleIdentifier::VIEW_TRANSITION, true],

            // Statement-only rules should return false
            [AtRuleIdentifier::CHARSET, false],
            [AtRuleIdentifier::IMPORT, false],
            [AtRuleIdentifier::LAYER, false],
            [AtRuleIdentifier::NAMESPACE, false],
        ];
    }
}
