<?php

namespace Paiement\Assertion;

use Application\Acl\Role;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\Dotation;
use Paiement\Entity\Db\TypeRessource;
use Framework\Authorize\AbstractAssertion;


/**
 * Description of BudgetAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class BudgetAssertion extends AbstractAssertion
{

    /**
     * Exemple
     */
    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Dotation:
                return $this->assertStructure($entity->getStructure());

            case $entity instanceof TypeRessource:
                return true; // déjà filtré par ce qu'il y a dessus!!

            case $entity instanceof Structure:
                return $this->assertStructure($entity);
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
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

}