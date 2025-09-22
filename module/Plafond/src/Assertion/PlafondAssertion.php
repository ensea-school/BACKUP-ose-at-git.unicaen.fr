<?php

namespace Plafond\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of PlafondAssertion
 *
 * @author UnicaenCode
 */
class PlafondAssertion extends AbstractAssertion
{

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
        switch ($action) {
            case 'index':
            case 'editer':
                return $this->assertStructure($role, $structure);
                break;
        }

        return true;
    }



    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Intervenant:
                switch ($privilege) {
                    case Privileges::PLAFONDS_DEROGATIONS_EDITION:
                        return $this->assertIntervenant($role, $entity);
                }
                break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::PLAFONDS_CONFIG_STRUCTURE:
                        return $this->assertStructure($role, $entity);
                }
                break;
        }

        return true;
    }



    protected function assertIntervenant($role, Intervenant $intervenant): bool
    {
        if ($intervenant->getStructure()) {
            return $this->assertStructure($role, $intervenant->getStructure());
        }

        return true;
    }



    protected function assertStructure(Role $role, Structure $structure): bool
    {
        if (!$role->getStructure()) return true;

        return $structure->inStructure($role->getStructure());
    }

}