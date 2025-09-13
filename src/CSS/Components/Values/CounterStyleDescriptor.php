<?php

namespace AVASTech\Demeter\CSS\Components\Values;

/**
 * Class CounterStyleDescriptor
 *
 * @package AVASTech\Demeter\CSS\Components\Values
 *
 */
enum CounterStyleDescriptor: string
{
    case SYSTEM           = 'system';
    case SYMBOLS          = 'symbols';
    case ADDITIVE_SYMBOLS = 'additive-symbols';
    case NEGATIVE         = 'negative';
    case PREFIX           = 'prefix';
    case SUFFIX           = 'suffix';
    case RANGE            = 'range';
    case PAD              = 'pad';
    case SPEAK_AS         = 'speak-as';
    case FALLBACK         = 'fallback';
}
