<?php

namespace AVASTech\Demeter\PHP\Components;

use AVASTech\Demeter\PHP\Components\Traits\HasName;
use AVASTech\Demeter\PHP\Definitions\Interfaces\ContextInterface;

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
    public function render(?ContextInterface $context = null): string
    {
        // TODO: Implement render() method.
    }
}
