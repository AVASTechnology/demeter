<?php

namespace AVASTech\Demeter\PHP\Components;

use AVASTech\Demeter\PHP\Components\Traits\HasName;

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
