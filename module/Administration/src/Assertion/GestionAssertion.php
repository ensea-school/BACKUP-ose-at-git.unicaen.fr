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


    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) {
            return false;
        }

        if ($this->getServiceContext()->getIntervenant()) {
            return false;
        }

        return true;
    }
}