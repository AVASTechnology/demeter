<?php

namespace AVASTech\Demeter\CSS\Formats;

/**
 * Class Selector
 *
 * @package AVASTech\Demeter\CSS\Formats
 */
class Selector implements Interfaces\Selector
{
    /**
     * @param string $selector
     * @return string
     */
    public function format(string $selector): string
    {
        return $selector;
    }
}
