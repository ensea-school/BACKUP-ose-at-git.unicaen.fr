<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Privilege;
use Zend\Permissions\Acl\Acl;
use Application\Acl\Role;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of ModificationServiceDuAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDuAssertion extends AbstractAssertion
{

    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $entity = null, $privilege = null)
    {
        if (! $role instanceof Role) return false;

        if ($entity instanceof Intervenant){
            switch ($privilege){
                case Privilege::MODIF_SERVICE_DU_EDITION:
                    return $this->assertIntervenant($entity);
                case Privilege::MODIF_SERVICE_DU_VISUALISATION:
                    return $this->assertIntervenant($entity);
            }
        }
        return true;
    }

    protected function assertController(Acl $acl, RoleInterface $role = null, $controller = null, $action = null, $privilege = null)
    {
        if ($controller == 'Application\Controller\ModificationServiceDu' && $action == 'saisir'){
            $intervenant = $this->getMvcEvent()->getParam('intervenant');
            if ($intervenant){
                return $this->assertIntervenant($intervenant);
            }       
        }
        parent::assertController($acl, $role, $controller, $action, $privilege);
    }


    protected function assertIntervenant( Intervenant $intervenant )
    {
        return $intervenant->getStatut()->hasPrivilege(Privilege::MODIF_SERVICE_DU_ASSOCIATION);
    }
}