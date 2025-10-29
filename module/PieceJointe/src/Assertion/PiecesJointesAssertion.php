<?php

namespace PieceJointe\Assertion;

use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use PieceJointe\Controller\PieceJointeController;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of PiecesJointesAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PiecesJointesAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;


    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);

        if (!$intervenant) return false;

        switch ($controller) {
            case PieceJointeController::class:
                switch ($action) {
                    case 'televerser':
                    case 'supprimer':
                        return $this->assertSaisie($intervenant);
                    case 'valider':
                    case 'devalider':
                        return $this->assertValidation($intervenant);
                }
                break;
        }

        return true;
    }



    protected function assertEntity(ResourceInterface $entity, ?string $privilege): bool
    {
        // @todo test par PJ en fonction des privilèges première instance/complémentaire,
        // Vérifier que l'assertion est bien exploitée
        return true;
    }



    protected function assertSaisie(Intervenant $intervenant): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        if ($feuilleDeRoute->get(WorkflowEtape::PJ_SAISIE)?->isAllowed()) {
            return true;
        }
        if ($feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_SAISIE)?->isAllowed()) {
            return true;
        }
        return false;
    }



    protected function assertValidation(Intervenant $intervenant): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        if ($feuilleDeRoute->get(WorkflowEtape::PJ_VALIDATION)?->isAllowed()) {
            return true;
        }
        if ($feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_VALIDATION)?->isAllowed()) {
            return true;
        }
        return false;
    }
}