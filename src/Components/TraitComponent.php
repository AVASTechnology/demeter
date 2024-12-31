<?php

namespace AVASTech\Demeter\Components;

use AVASTech\Demeter\Components\Traits\HasName;

/**
 * Class TraitComponent
 *
 * @package AVASTech\Demeter\Components
 */
class TraitComponent extends AbstractComponent
{
    use HasName;

    /**
     * @inheritDoc
     */
    public function render(string $indentation = ''): string
    {
        // TODO: Implement render() method.
    }
}
