<?php

namespace Mission\Assertion;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeAssertion extends AbstractAssertion implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;


    protected function assertController (string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam('intervenant');

        if ($intervenant) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
            $wfEtape = $feuilleDeRoute->get(WorkflowEtape::MISSION_PRIME);

            if (!$wfEtape || !$wfEtape->isAllowed()){
                return false;
            }
        }

        return true;
    }

}