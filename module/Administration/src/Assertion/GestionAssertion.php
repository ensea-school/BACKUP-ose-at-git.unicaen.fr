<?php

namespace Administration\Assertion;

use Application\Acl\Role;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of GestionAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class GestionAssertion extends AbstractAssertion
{
    /**
     * @var boolean[]
     */
    protected $cache=[];



    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $role = $this->getRole();

        $key = $controller.'.'.$action.'>'.$privilege;

        if (!isset($this->cache[$key])){
            $this->cache[$key] = $this->asserts([
                $role instanceof Role,
                $this->assertIntervenant($role, $privilege)
            ]);
        }
        return $this->cache[$key];
    }



    protected function assertIntervenant( Role $role, $privilege ): bool
    {
        return $this->asserts([
            !$privilege || $role->hasPrivilege($privilege), // pareil si le rôle ne possède pas le privilège adéquat
            !$role->getIntervenant() // les intervenants n'ont pour le moment pas accès au menu Gestion
        ]);
    }

}