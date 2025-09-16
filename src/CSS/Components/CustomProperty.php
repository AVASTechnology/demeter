<?php

namespace AVASTech\Demeter\CSS\Components;

/**
 * Class CustomProperty
 *
 * @package AVASTech\Demeter\CSS\Components
 */
class CustomProperty implements Interfaces\Property
{
    /**
     * @var string $name
     */
    public string $name = 'CUSTOM_PROPERTY';

    /**
     * @param string $value
     */
    public function __construct(public string $value)
    {
        //
    }
}
