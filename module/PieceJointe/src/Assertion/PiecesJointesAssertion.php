<?php

namespace PieceJointe\Assertion;

use PieceJointe\Entity\Db\PieceJointe;
use PieceJointe\Service\Traits\TblPieceJointeServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use PieceJointe\Controller\PieceJointeController;
use Unicaen\Framework\Authorize\UnAuthorizedException;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;


class PiecesJointesAssertion extends AbstractAssertion
{
    use WorkflowServiceAwareTrait;
    use TblPieceJointeServiceAwareTrait;


    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);
        $pieceJointe = $this->getParam(PieceJointe::class);

        if (!$intervenant) return false;

        switch ($controller . '.' . $action) {
            case PieceJointeController::class . '.index':
            case PieceJointeController::class . '.infos':
            case PieceJointeController::class . '.lister':
            case PieceJointeController::class . '.getPiecesJointes':
            case PieceJointeController::class . '.televerser':
                return $this->isEtapeAccessible($intervenant);
            case PieceJointeController::class . '.supprimer':
                return $this->assertEdition($intervenant, $pieceJointe);
            case PieceJointeController::class . '.validerFichier':
            case PieceJointeController::class . '.valider':
            case PieceJointeController::class . '.refuser':
            case PieceJointeController::class . '.devalider':
                return $this->assertValidation($intervenant, $pieceJointe);
            default:
                throw new UnAuthorizedException('Action de contrôleur ' . $controller . ':' . $action . ' non traitée');
        }
    }



    /**
     * @param Intervenant $intervenant
     * @return bool
     */
    public function isEtapeAccessible(mixed $intervenant): bool
    {
        if (!$intervenant) {
            return false;
        }

        $wfEtape = $this
            ->getServiceWorkflow()
            ->getFeuilleDeRoute($intervenant)
            ->get(WorkflowEtape::PJ_COMPL_SAISIE);

        return $wfEtape?->isAllowed() ?? false;
    }



    /**
     * @param Intervenant $intervenant
     * @param PieceJointe $pieceJointe
     * @return bool
     */

    protected function assertEdition(Intervenant $intervenant, PieceJointe $pieceJointe): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $tblPieceJointe = $this->getServiceTblPieceJointe()->getRepo()->findOneBy(['pieceJointe' => $pieceJointe]);

        if ($tblPieceJointe->isDemandeApresRecrutement() &&
            $feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_SAISIE)?->isAllowed()) {

            return true;

        } else if ($feuilleDeRoute->get(WorkflowEtape::PJ_SAISIE)?->isAllowed()) {
            return true;
        }

        return false;
    }



    /**
     * @param Intervenant $intervenant
     * @param PieceJointe $pieceJointe
     * @return bool
     */

    protected function assertValidation(Intervenant $intervenant, PieceJointe $pieceJointe): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $tblPieceJointe = $this->getServiceTblPieceJointe()->getRepo()->findOneBy(['pieceJointe' => $pieceJointe]);

        if ($tblPieceJointe->isDemandeApresRecrutement() &&
            $feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_VALIDATION)?->isAllowed()) {
            return true;

        } else if ($feuilleDeRoute->get(WorkflowEtape::PJ_VALIDATION)?->isAllowed()) {
            return true;
        }

        return false;
    }


}