<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

use AVASTech\Demeter\CSS\Components\Declaration;
use AVASTech\Demeter\CSS\Formats\Interfaces\Declaration as DeclarationFormat;
use AVASTech\Demeter\CSS\Formats\Interfaces\DeclarationBlock as DeclarationBlockFormat;

/**
 * Interface DeclarationBlockFormatInterface
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
interface DeclarationBlock
{
    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $indent { get; set; }

    /**
     * @var string|\Closure|null
     */
    public string|\Closure|null $endOfLine { get; set; }

    /**
     * @var DeclarationFormat|null
     */
    public ?DeclarationFormat $declarationFormat { get; set; }

    /**
     * @param Declaration[] $declarations
     * @return string
     */
    public function format(array $declarations, int $nestLevel = 0): string;
}
