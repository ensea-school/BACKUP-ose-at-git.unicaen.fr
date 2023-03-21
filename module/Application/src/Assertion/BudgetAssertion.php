<?php

namespace Application\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Dotation;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeRessource;
use Application\Provider\Privilege\Privileges;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


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
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
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
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        if ($controller == 'Application\Controller\Budget' && $action == 'tableau-de-bord') {
            return !$role->getStructure(); // on n'a accès que si on n'est pas dans une structure spécifique!!
        }

        return true;
    }



    protected function assertStructure(Structure $structure)
    {
        $rs = $this->getRole()->getStructure();

        return (!$rs || $rs == $structure);
    }

}