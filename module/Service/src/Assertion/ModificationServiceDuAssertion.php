<?php

namespace Service\Assertion;

use Application\Provider\Privileges;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Service\Controller\ModificationServiceDuController;


/**
 * Description of ModificationServiceDuAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDuAssertion extends AbstractAssertion
{

    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
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



    protected function assertController(string $controller, ?string $action): bool
    {
        if ($controller == ModificationServiceDuController::class && $action == 'saisir') {
            $intervenant = $this->getParam('intervenant');
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