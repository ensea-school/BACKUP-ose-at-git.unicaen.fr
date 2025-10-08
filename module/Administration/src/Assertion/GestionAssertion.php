<?php

namespace Administration\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;


/**
 * Description of GestionAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;


    protected function assertController(string $controller, ?string $action): bool
    {
        if ($this->getServiceContext()->getIntervenant()) {
            return false;
        }

        return true;
    }
}