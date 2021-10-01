<?php

namespace Plafond\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Structure;
use UnicaenAuth\Assertion\AbstractAssertion;
use Zend\Permissions\Acl\Resource\ResourceInterface;


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
    protected function assertController($controller, $action = null, $privilege = null)
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



    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {

//        switch (true) {
//            case $entity instanceof VotreEntite:
//                switch ($privilege) {
//                    case Privileges::VOTRE_PRIVILEGE: // Attention à bien avoir généré le fournisseur de privilèges si vous utilisez la gestion des privilèges d'UnicaenAuth
//                        return $this->assertVotreAssertion($role, $entity);
//                }
//                break;
//        }

        return true;
    }



    protected function assertStructure(Role $role, Structure $structure): bool
    {
        if (!$role->getStructure()) return true;

        return $role->getStructure() == $structure;
    }

}