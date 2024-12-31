<?php

namespace AVASTech\Demeter\Definitions;

/**
 * Class Type
 *
 * @package AVASTech\Demeter\Definitions
 */
enum Type: string
{
    case BUILT_IN_ARRAY    = 'array';
    case BUILT_IN_BOOL     = 'bool';
    case BUILT_IN_NULL     = 'null';
    case BUILT_IN_CALLABLE = 'callable';
    case BUILT_IN_FLOAT    = 'float';
    case BUILT_IN_INT      = 'int';
    case BUILT_IN_STRING   = 'string';
    case BUILT_IN_ITERABLE = 'iterable';
    case BUILT_IN_OBJECT   = 'object';
    case BUILT_IN_SELF     = 'self';
    case BUILT_IN_PARENT   = 'parent';
    case BUILT_IN_MIXED    = 'mixed';
}
