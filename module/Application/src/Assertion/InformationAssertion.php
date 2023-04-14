<?php

namespace Application\Assertion;

use Application\Acl\Role;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of InformationAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class InformationAssertion extends AbstractAssertion
{
    const INFO_ONLY_STRUCTURE = 'info-only-structure';
    const AIDE_INTERVENANT    = 'aide-intervenant';

    /**
     * @param ResourceInterface $resource
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertOther(ResourceInterface $resource = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;


        switch($privilege){
            case self::INFO_ONLY_STRUCTURE:
                return (boolean)$role->getStructure();
            break;
            case self::AIDE_INTERVENANT:
                return (boolean)$role->getIntervenant();
            break;
        }

        return true;
    }

}