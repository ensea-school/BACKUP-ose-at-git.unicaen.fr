<?php

namespace Mission\Assertion;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Workflow\Entity\Db\WfEtape;
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


    protected function assertController ($controller, $action = null, $privilege = null): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_MISSION_PRIME, $intervenant);
            $wfOk          = $workflowEtape && $workflowEtape->isAtteignable();
            if (!$wfOk) return false;
        }

        return true;
    }

}