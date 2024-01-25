<?php

namespace PieceJointe\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use PieceJointe\Controller\PieceJointeController;
use UnicaenPrivilege\Assertion\AbstractAssertion;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur


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
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        switch ($controller) {
            case PieceJointeController::class:
                switch ($action) {
                    case 'index':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_VISUALISATION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    break;
                    case 'televerser':
                    case 'supprimer':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_EDITION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    break;
                    case 'valider':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_VALIDATION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    break;
                    case 'devalider':
                        if (!$this->assertPriv(Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION)) return false;

                        return $this->assertPieceJointeAction($intervenant);
                    break;
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
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        return true;
    }



    /*assertion refonte dossier*/

    protected function assertPrivilege($privilege, $subPrivilege = null)
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        switch ($privilege) {
            case Privileges::DOSSIER_IDENTITE_EDITION:
                return $this->assertEditionDossierContact($intervenant);
            break;
        }
    }



    protected function assertEditionDossierContact(Intervenant $intervenant)
    {
        return true;
    }



    protected function assertDossierEdition(Intervenant $intervenant = null)
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertPieceJointeAction(Intervenant $intervenant = null)
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_PJ_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape || !$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }



    protected function assertPriv($privilege)
    {
        $role = $this->getRole();
        if (!$role instanceof Role) return false;

        return $role->hasPrivilege($privilege);
    }
}