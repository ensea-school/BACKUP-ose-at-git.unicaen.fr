<?php

namespace Paiement\Assertion;

use Application\Acl\Role;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\TypeModulateur;
use Paiement\Entity\Db\TypeModulateurStructure;
use Framework\Authorize\AbstractAssertion;


class ModulateurAssertion extends AbstractAssertion
{

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof TypeModulateur:
                return $this->assertTypeModulateur($role, $entity);

            case $entity instanceof Structure:
                return $this->assertStructure($entity);

            case $entity instanceof TypeModulateurStructure:
                return $this->assertTypeModulateurStructure($role, $entity);
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        return true;
    }



    protected function assertStructure(Structure $structure): bool
    {
        $rs = $this->getRole()->getStructure();

        return (!$rs || $structure->inStructure($rs));
    }



    protected function assertTypeModulateur(Role $role, TypeModulateur $typeModulateur): bool
    {
        $rs = $role->getStructure();
        if (!$rs) return true;
        $valRet = false;

        /** @var Structure[] $tblStructure */
        $tblStructure = $typeModulateur->getStructure();
        foreach ($tblStructure as $str) if ($str->inStructure($rs)) $valRet = true;

        return $valRet;
    }



    protected function assertTypeModulateurStructure(Role $role, TypeModulateurStructure $tms): bool
    {
        return $this->asserts([
            $this->assertTypeModulateur($role, $tms->getTypeModulateur()),
            $this->assertStructure($tms->getStructure()),
        ]);
    }
}