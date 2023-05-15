<?php

namespace Paiement\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Structure;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\TypeModulateur;
use UnicaenPrivilege\Assertion\AbstractAssertion;


class ModulateurAssertion extends AbstractAssertion
{

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {

        $role= $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
       if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof TypeModulateur:
                return $this->assertTypeModulateur($role,$entity);

            case $entity instanceof Structure:
                return $this->assertStructure($entity);
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;
        
        return true;
    }



    protected function assertStructure( Structure $structure )
    {
        $rs = $this->getRole()->getStructure();
        return (! $rs || $rs == $structure);
    }

    protected function assertTypeModulateur( Role $role, TypeModulateur $typeModulateur )
    {
        $rs=$role->getStructure();
        if (!$rs) return true;
        $valRet=false;
        $tblStructure=$typeModulateur->getStructure();
        foreach($tblStructure as $str) if ($rs == $str) $valRet=true;
        return $valRet;
    }

}