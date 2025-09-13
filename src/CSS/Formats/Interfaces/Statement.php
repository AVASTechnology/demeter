<?php

namespace AVASTech\Demeter\CSS\Formats\Interfaces;

/**
 * Interface Statement
 *
 * @package AVASTech\Demeter\CSS\Formats\Interfaces
 */
interface Statement
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
     * @var string|\Closure|null
     */
    public string|\Closure|null $onNewStatement { get; set; }
}
