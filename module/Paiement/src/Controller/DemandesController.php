<?php

namespace Paiement\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\MiseEnPaiement;
use Paiement\Entity\Db\TypeRessource;
use Paiement\Service\DemandesServiceAwareTrait;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;
use Workflow\Entity\Db\Validation;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of DemandesController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DemandesController extends AbstractController
{
    use WorkflowServiceAwareTrait;
    use ContextServiceAwareTrait;
    use DemandesServiceAwareTrait;
    use StructureServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ParametresServiceAwareTrait;


    protected function initFilters(): void
    {
        $this->em()->getFilters()->enable('historique')->init([
                                                                  MiseEnPaiement::class,
                                                                  VolumeHoraire::class,
                                                                  ServiceReferentiel::class,
                                                                  VolumeHoraireReferentiel::class,
                                                                  Validation::class,
                                                                  TypeRessource::class,
                                                              ]);
    }



    public function ajouterDemandesMiseEnPaiementAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            return false;
        }
        if ($this->getRequest()->isPost() && !$role->getIntervenant()) {
            $demandes                         = $this->axios()->fromPost();
            $demandesApprouveesBudgetairement = $this->getServiceDemandes()->verifierBudgetDemandeMiseEnPaiement($demandes);
            $error                            = 0;
            $errorBudget = round(count($demandes) - count($demandesApprouveesBudgetairement));

            $success                          = 0;
            foreach ($demandesApprouveesBudgetairement as $demande) {
                try {
                    $this->getServiceDemandes()->verifierValiditeDemandeMiseEnPaiement($intervenant, $demande);
                    $this->getServiceDemandes()->ajouterDemandeMiseEnPaiement($intervenant, $demande);
                } catch (\Exception $e) {
                    if ($e->getCode() == 3) {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                    } else {
                        $this->flashMessenger()->addErrorMessage($e->getMessage());
                        $error++;
                    }
                    continue;
                }
                $success++;
            }
            //Mise à jour des tableaux de bord nécessaires
            $this->updateTableauxBord($intervenant);
            //Traitement des messages de succes ou d'erreur (Toast)
            if ($success == 0) {
                $this->flashMessenger()->addInfoMessage('Aucune demande de mise en paiement à effectuer pour cette composante');
            }
            //Demandes de mise en paiement effectuées
            if ($success > 0) {
                if ($success > 1) {
                    $this->flashMessenger()->addSuccessMessage($success . " demandes de mise en paiement effectuées pour cette composante.");
                } else {
                    $this->flashMessenger()->addSuccessMessage($success . " demande de mise en paiement effectuée pour cette composante.");
                }
            }
            //Erreur de demande de mise en paiement pour mauvais paramètrage de centre de cout ou de domaine fonctionnel
            if ($error > 0) {
                if ($error > 1) {
                    $this->flashMessenger()->addErrorMessage("Attention, $error demandes de mise en paiement n'ont pas pu être traitées pour cette composante.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Attention, $error demande de mise en paiement n'a pas pu être traitée pour cette composante.");
                }
            }
            //Erreur de mise en paiement pour raison de dépassement de budget
            if ($errorBudget > 0) {
                if ($errorBudget > 1) {
                    $this->flashMessenger()->addErrorMessage("Attention, $errorBudget demandes de mise en paiement n'ont pas pu être traitées pour cette composante car votre budget ne permet plus d'en faire la demande.");
                } else {
                    $this->flashMessenger()->addErrorMessage("Attention, $errorBudget demande de mise en paiement n'a pas pu être traitée pour cette composante car votre budget ne permet plus d'en faire la demande.");
                }
            }

            return true;
        }

        return false;
    }



    public function getDemandesMiseEnPaiementAction()
    {
        $structureRole = null;
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        /**
         * @var Intervenant $intervenant
         */
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant ne peut pas récuperer les datas de demande de mise en paiement
        if ($role->getIntervenant()) {
            return new AxiosModel([]);
        }
        //$this->updateTableauxBord($intervenant);
        if ($role->getPerimetre()->isComposante()) {
            $structureRole = $role->getStructure();
        }

        $servicesAPayer = $this->getServiceDemandes()->getDemandeMiseEnPaiementResume($intervenant, $structureRole);

        return new AxiosModel($servicesAPayer);
    }



    public function demandeMiseEnPaiementAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant n'a pas le droit de voir cette page de demande de mise en paiement
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            $this->redirect()->toRoute('intervenant/mise-en-paiement/visualisation', ['intervenant' => $intervenant->getId()]);
        }
        $intervenantStructure = ($intervenant->getStructure())?$intervenant->getStructure()->getLibelleCourt():'';

        return compact('intervenant', 'intervenantStructure');
    }



    public function supprimerDemandeMiseEnPaiementAction()
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $this->initFilters();
        $idDmep      = $this->params()->fromRoute('mise-en-paiement');
        $intervenant = $this->getEvent()->getParam('intervenant');
        //Un intervenant ne peut pas supprimer des demandes de mise en paiement
        if ($role->getIntervenant()) {
            //On redirige vers la visualisation des mises en paiement
            return false;
        }
        //on supprimer la demande de mise en paiement
        try {
            $this->getServiceDemandes()->supprimerDemandeMiseEnPaiement($idDmep);
            $this->flashMessenger()->addSuccessMessage("Demande de mise en paiement supprimer.");
            $this->updateTableauxBord($intervenant);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());

            return false;
        }

        return true;
    }



    function demandeMiseEnPaiementLotAction()
    {
        $structures        = $this->getServiceStructure()->getStructuresDemandeMiseEnPaiement();
        $canMiseEnPaiement = $this->isAllowed(Privileges::getResourceId(Privileges::MISE_EN_PAIEMENT_MISE_EN_PAIEMENT));
        if ($this->getRequest()->isPost()) {
            //On récupère les données post notamment la structure recherchée
            $idStructure  = $this->getRequest()->getPost('structure');
            $structure    = $this->em()->find(Structure::class, $idStructure);
            $intervenants = $this->getServiceDemandes()->getListByStructure($structure);


            return new AxiosModel($intervenants);
        }

        $vm = new VueModel();
        $vm->setTemplate('paiement/demande-mise-en-paiement-lot');
        $vm->setVariables(['canMiseEnPaiement' => $canMiseEnPaiement, 'structures' => $structures]);

        return $vm;
    }



    function processDemandeMiseEnPaiementLotAction()
    {

        if ($this->getRequest()->isPost()) {
            $datasIntervenant = $this->getRequest()->getPost('intervenant');
            if (empty($datasIntervenant)) {
                return false;
            }
            $intervenantIds = array_keys($datasIntervenant);
            foreach ($intervenantIds as $id) {
                $intervenant = $this->getServiceIntervenant()->get($id);
                if ($intervenant) {
                    $this->getServiceDemandes()->demandesMisesEnPaiementIntervenant($intervenant);
                    $this->updateTableauxBord($intervenant);
                }
            }
            $this->flashMessenger()->addSuccessMessage("Les demandes de mise en paiement ont bien été effectuée");

            return $this->redirect()->toRoute('paiement/demande-mise-en-paiement-lot');
        }
    }



    private function updateTableauxBord(Intervenant $intervenant): void
    {
        $this->getServiceWorkflow()->calculerTableauxBord('paiement', $intervenant);
    }

}