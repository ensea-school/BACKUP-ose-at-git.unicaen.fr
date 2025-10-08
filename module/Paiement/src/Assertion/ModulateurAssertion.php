<?php

namespace Paiement\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\TypeModulateur;
use Paiement\Entity\Db\TypeModulateurStructure;


class ModulateurAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof TypeModulateur:
                return $this->assertTypeModulateur($entity);

            case $entity instanceof Structure:
                return $this->assertStructure($entity);

            case $entity instanceof TypeModulateurStructure:
                return $this->assertTypeModulateurStructure($entity);
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
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        return true;
    }



    protected function assertStructure(Structure $structure): bool
    {
        $rs = $this->getServiceContext()->getStructure();

        return (!$rs || $structure->inStructure($rs));
    }



    protected function assertTypeModulateur(TypeModulateur $typeModulateur): bool
    {
        $rs = $this->getServiceContext()->getStructure();
        if (!$rs) return true;
        $valRet = false;

        /** @var Structure[] $tblStructure */
        $tblStructure = $typeModulateur->getStructure();
        foreach ($tblStructure as $str) if ($str->inStructure($rs)) $valRet = true;

        return $valRet;
    }



    protected function assertTypeModulateurStructure(TypeModulateurStructure $tms): bool
    {
        return $this->asserts([
            $this->assertTypeModulateur($tms->getTypeModulateur()),
            $this->assertStructure($tms->getStructure()),
        ]);
    }
}