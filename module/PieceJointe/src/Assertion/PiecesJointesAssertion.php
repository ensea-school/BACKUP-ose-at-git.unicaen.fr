<?php

namespace PieceJointe\Assertion;

use Application\Provider\Privileges;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
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


    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController(string $controller, ?string $action): bool
    {
        $intervenant = $this->getParam(Intervenant::class);

        switch ($controller) {
            case PieceJointeController::class:
                switch ($action) {
                    case 'index':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_VISUALISATION)) return false;
                        if (!$intervenant) return false;
                        return $this->assertPieceJointeAction($intervenant);
                    case 'televerser':
                    case 'supprimer':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_EDITION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    case 'valider':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_VALIDATION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    case 'devalider':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                }
                break;
        }

        return true;
    }



    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(?ResourceInterface $entity, $privilege = null): bool
    {
        return true;
    }



    protected function assertDossierEdition(Intervenant $intervenant): bool
    {
        if (!$this->assertEtapeAtteignable(WorkflowEtape::DONNEES_PERSO_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertPieceJointeAction(Intervenant $intervenant): bool
    {
        if (!$this->assertEtapeAtteignable(WorkflowEtape::PJ_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant): bool
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        return $feuilleDeRoute->get($etape)?->isAllowed() ?: false;
    }



    protected function assertPriv($privilege): bool
    {
        return $this->authorize->isAllowedPrivilege($privilege);
    }
}