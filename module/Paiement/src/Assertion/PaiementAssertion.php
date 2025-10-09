<?php

namespace Paiement\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Unicaen\Framework\Navigation\Page;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of PaiementAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PaiementAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;


    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController (string $controller, ?string $action): bool
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        switch ($action) {
            case 'demandemiseenpaiement':
                return $this->assertEtapeAtteignable(WorkflowEtape::DEMANDE_MEP, $intervenant);
            break;
            case 'visualisationmiseenpaiement':

            break;
            case 'editionmiseenpaiement':

            break;
            case 'etatpaiement':
                if ($this->getServiceContext()->getIntervenant()) return false; // pas pour les intervenants
            break;
            case 'extractionpaieprime':
                return $this->assertEtapeAtteignable(WorkflowEtape::MISSION_PRIME, $intervenant);
            break;
            case  'miseenpaiement':

            break;
        }

        return true;
    }


    protected function assertPage (Page $page): bool
    {
        $page = $page->getData();
        if (isset($page['workflow-etape-code'])) {
            $etape       = $page['workflow-etape-code'];
            $intervenant = $this->getMvcEvent()->getParam('intervenant');

            if (!$this->assertEtapeAtteignable($etape, $intervenant)) {
                return false;
            }
        }

        return true;
    }



    protected function assertEtapeAtteignable (string $etape, ?Intervenant $intervenant, ?Structure $structure = null): bool
    {
        if ($intervenant) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant, $structure);
            $workflowEtape = $feuilleDeRoute->get($etape);
            if (!$workflowEtape || !$workflowEtape->isAllowed()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}