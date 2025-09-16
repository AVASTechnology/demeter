<?php

namespace AVASTech\Demeter\CSS\Components;

/**
 * Class AtRuleIdentifier
 *
 * @package AVASTech\Demeter\CSS\Components
 *
 */
enum AtRuleIdentifier: string implements Interfaces\AtRuleIdentifier
{
    case CHARSET             = '@charset'; // Must be at the top of the file.
    case COLOR_PROFILE       = '@color-profile';
    case CONTAINER           = '@container';
    case COUNTER_STYLE       = '@counter-style';
    case FONT_FACE           = '@font-face';
    case FONT_FEATURE_VALUES = '@font-feature-values';
    case FONT_PALETTE_VALUES = '@font-palette-values';
    case IMPORT              = '@import';
    case KEYFRAMES           = '@keyframes';
    case LAYER               = '@layer';
    case MEDIA               = '@media';
    case NAMESPACE           = '@namespace';
    case PAGE                = '@page';
    case POSITION_TRY        = '@position-try';
    case PROPERTY            = '@property';
    case SCOPE               = '@scope';
    case STARTING_STYLE      = '@starting-style';
    case SUPPORTS            = '@supports';
    case VIEW_TRANSITION     = '@view-transition';

    /**
     * @var array<string> Statement-style identifiers
     */
    public const array STATEMENT_RULES = [
        self::CHARSET,
        self::IMPORT,
        self::LAYER,
        self::NAMESPACE,
    ];

    /**
     * @var array<string> Statement-style identifiers
     */
    public const array BLOCK_RULES = [
        self::COLOR_PROFILE,
        self::COUNTER_STYLE,
        self::CONTAINER,
        self::FONT_FACE,
        self::FONT_FEATURE_VALUES,
        self::FONT_PALETTE_VALUES,
        self::KEYFRAMES,
        self::MEDIA,
        self::PAGE,
        self::POSITION_TRY,
        self::PROPERTY,
        self::SCOPE,
        self::STARTING_STYLE,
        self::SUPPORTS,
        self::VIEW_TRANSITION,
    ];

    /**
     * @var array<string> Statement-style identifiers
     */
    public const array NESTED_BLOCK_RULES = [
        self::CONTAINER,
        self::FONT_FEATURE_VALUES,
        self::KEYFRAMES,
        self::MEDIA,
        self::SCOPE,
        self::STARTING_STYLE,
        self::SUPPORTS,
    ];

    /**
     * @param string $css
     * @return self
     */
    public static function determineIdentifier(string $css): self
    {
        foreach (self::cases() as $case) {
            if (str_starts_with($css, $case->value)) {
                return $case;
            }
        }

        throw new \InvalidArgumentException(
            sprintf(
                'The CSS string "%s" does not match any known at-rule identifier.',
                $css
            )
        );
    }

    /**
     * @return bool
     */
    public function isStatement(): bool
    {
        return in_array($this, self::STATEMENT_RULES);
    }

    /**
     * @return bool
     */
    public function isBlock(): bool
    {
        return in_array($this, self::BLOCK_RULES);
    }

    /**
     * @return bool
     */
    public function nestsStatements(): bool
    {
        return in_array($this, self::NESTED_BLOCK_RULES);
    }

    /**
     * @return bool
     */
    public function wrapRule(): bool
    {
        return false;
    }
}
