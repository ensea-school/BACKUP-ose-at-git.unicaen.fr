<?php

namespace Paiement\Assertion;

use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Controller\DemandesController;
use Paiement\Controller\PaiementController;
use Unicaen\Framework\Authorize\AbstractAssertion;
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
    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam('intervenant');
        /* @var $intervenant Intervenant */

        switch ($controller . '.' . $action) {

            case PaiementController::class . '.visualisationMiseEnPaiement':
            case PaiementController::class . '.editionMiseEnPaiement':
            case PaiementController::class . '.detailsCalculs':
            case DemandesController::class . '.demandeMiseEnPaiement':
            case DemandesController::class . '.getDemandesMiseEnPaiement':
                return $this->assertEtapeAtteignable(WorkflowEtape::DEMANDE_MEP, $intervenant);

            case PaiementController::class . 'etatPaiement':
                if ($this->getServiceContext()->getIntervenant()) return false; // pas pour les intervenants
                return true;

            case PaiementController::class . '.extractionPaiePrime':
                return $this->assertEtapeAtteignable(WorkflowEtape::MISSION_PRIME, $intervenant);

            case PaiementController::class . '.miseEnPaiement':
                return true;
        }

        // sécu renforcée
        return false;
    }



    protected function assertEtapeAtteignable(string $etape, ?Intervenant $intervenant, ?Structure $structure = null): bool
    {
        if ($intervenant) {
            $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant, $structure);
            $workflowEtape  = $feuilleDeRoute->get($etape);
            if (!$workflowEtape || !$workflowEtape->isAllowed()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}