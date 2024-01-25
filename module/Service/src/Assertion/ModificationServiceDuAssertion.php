<?php

namespace Service\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Service\Controller\ModificationServiceDuController;
use UnicaenPrivilege\Assertion\AbstractAssertion;


/**
 * Description of ModificationServiceDuAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDuAssertion extends AbstractAssertion
{

    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $role = $this->getRole();
        if (!$role instanceof Role) return false;

        if ($entity instanceof Intervenant) {
            switch ($privilege) {
                case Privileges::MODIF_SERVICE_DU_EDITION:
                    return $this->assertIntervenant($entity);
                case Privileges::MODIF_SERVICE_DU_VISUALISATION:
                    return $this->assertIntervenant($entity);
            }
        }

        return true;
    }



    protected function assertController($controller, $action = null, $privilege = null)
    {
        if ($controller == ModificationServiceDuController::class && $action == 'saisir') {
            $intervenant = $this->getMvcEvent()->getParam('intervenant');
            if ($intervenant) {
                return $this->assertIntervenant($intervenant);
            }
        }

        return true;
    }



    protected function assertIntervenant(Intervenant $intervenant)
    {
        return $intervenant->getStatut()->getModificationServiceDu();
    }
}