<?php

namespace Application\Assertion;

use Application\Provider\Privilege\Privileges;
use UnicaenAuth\Assertion\AbstractAssertion;
use Application\Acl\Role;



/**
 * Description of GestionAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionAssertion extends AbstractAssertion
{

    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role        = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        if ($role->getIntervenant()) return false; // les intervenants n'ont pour le moment pas accès au menu Gestion

        return true;
    }


}