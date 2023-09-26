<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Mission\Entity\Db\OffreEmploi;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PrimeAssertion extends AbstractAssertion implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use WorkflowServiceAwareTrait;


    protected function assertController ($controller, $action = null, $privilege = null)
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