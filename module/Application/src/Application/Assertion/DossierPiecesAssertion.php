<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges; // sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenAuth\Assertion\AbstractAssertion;


/**
 * Description of DossierPiecesAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class DossierPiecesAssertion extends AbstractAssertion
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
            case 'Application\Controller\Dossier':
                switch ($action) {
                    case 'voir':
                        if (!$this->assertPriv(Privileges::DOSSIER_VISUALISATION)) return false;
                        return $this->assertDossierEdition($intervenant);
                    break;
                    case 'modifier':
                        if (!$this->assertPriv(Privileges::DOSSIER_EDITION)) return false;
                        return $this->assertDossierEdition($intervenant);
                    break;
                }
            break;
            case 'Application\Controller\Validation':
                switch($action){
                    case 'dossier':
                        if (!$this->assertPriv(Privileges::DOSSIER_VALIDATION)) return false;
                        return $this->assertDossierValidation($intervenant);
                    break;
                }
            break;
        }

        return true;
    }



    protected function assertPriv( $privilege )
    {
        return $this->isAllowed(Privileges::getResourceId($privilege));
    }



    protected function assertDossierEdition(Intervenant $intervenant = null)
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_DONNEES_PERSO_SAISIE, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertDossierValidation(Intervenant $intervenant = null)
    {
        if (!$this->assertEtapeAtteignable(WfEtape::CODE_DONNEES_PERSO_VALIDATION, $intervenant)) {
            return false;
        }

        return true;
    }



    protected function assertEtapeAtteignable($etape, Intervenant $intervenant = null)
    {
        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape($etape, $intervenant);
            if (!$workflowEtape->isAtteignable()) { // l'étape doit être atteignable
                return false;
            }
        }

        return true;
    }
}