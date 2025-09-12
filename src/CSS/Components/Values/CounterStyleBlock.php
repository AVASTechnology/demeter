<?php

namespace AVASTech\Demeter\CSS\Components\Values;

use AVASTech\Demeter\CSS\Components\Interfaces\AtRuleBlock;


/**
 * Class CounterStyleBlock
 *
 * @package AVASTech\Demeter\CSS\Components\Values
 */
class CounterStyleBlock implements AtRuleBlock
{

    /**
     * @param string $block
     */
    public function __construct(public string $block)
    {
        //
    }



}
