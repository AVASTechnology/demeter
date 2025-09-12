<?php

namespace AVASTech\Demeter\CSS\Components;

use AVASTech\Demeter\CSS\Components\Interfaces\Property;

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
     * @param string $name
     */
    public function __construct(public string $value)
    {
        //
    }


}
