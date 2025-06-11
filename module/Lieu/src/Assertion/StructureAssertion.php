<?php

namespace Lieu\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of StructureAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureAssertion extends AbstractAssertion
{

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::STRUCTURES_ADMINISTRATION_EDITION:
                        //case Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION:
                        return $this->assertStructure($role, $entity);
                }
            break;
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

        $structure = $this->getMvcEvent()->getParam('structure');
        /* @var $structure Structure */

        // Si c'est bon alors on affine...
        if ($structure) switch ($action) {
            case 'saisie':
            case 'delete':
                return $this->assertStructure($role, $structure);
            break;
        }

        return true;
    }



    protected function assertStructure(Role $role, Structure $structure): bool
    {
        if (!$role->getStructure()) return true;

        return $structure->inStructure($role->getStructure());
    }

}