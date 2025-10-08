<?php

namespace Lieu\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Utilisateur\Acl\Role;


/**
 * Description of StructureAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class StructureAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::STRUCTURES_ADMINISTRATION_EDITION:
                        //case Privileges::STRUCTURES_ADMINISTRATION_VISUALISATION:
                        return $this->assertStructure($entity);
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
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        $structure = $this->getMvcEvent()->getParam('structure');
        /* @var $structure Structure */

        // Si c'est bon alors on affine...
        if ($structure) switch ($action) {
            case 'saisie':
            case 'delete':
                return $this->assertStructure($structure);
            break;
        }

        return true;
    }



    protected function assertStructure(Role $role, Structure $structure): bool
    {
        $curStructure = $this->getServiceContext()->getStructure();

        if (!$curStructure) return true;

        return $structure->inStructure($curStructure);
    }

}