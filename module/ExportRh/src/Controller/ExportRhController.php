<?php

namespace ExportRh\Controller;


use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Dossier\Service\Traits\DossierServiceAwareTrait;
use ExportRh\Form\Traits\ExportRhFormAwareTrait;
use ExportRh\Service\ExportRhService;
use ExportRh\Service\ExportRhServiceAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;

class ExportRhController extends AbstractController
{

    use ExportRhServiceAwareTrait;
    use ContextServiceAwareTrait;
    use DossierServiceAwareTrait;
    use ExportRhFormAwareTrait;
    use IntervenantServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use ParametresServiceAwareTrait;


    /**
     * @var ExportRhService $exportRhService
     */
    protected $exportRhService;



    public function __construct(ExportRhService $exportRhService)
    {
        $this->exportRhService = $exportRhService;
    }



    public function priseEnChargeAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $intervenant = $this->getEvent()->getParam('intervenant');

                if (!$intervenant) {
                    throw new \LogicException('Intervenant non précisé ou inexistant');
                }

                $posts = $this->getRequest()->getPost();
                if (empty($posts['connecteurForm']['statut']) || $posts['connecteurForm']['statut'] != '') {
                    $codeStatut = $posts['connecteurForm']['statut'];
                } else {
                    throw new \Exception("Le statut de prise en charge n'a pas été précisé");
                }

                $matricule = $this->exportRhService->priseEnChargeIntrervenantRh($intervenant, $posts);

                if ($matricule !== false) {
                    $this->exportRhService->cloreDossier($intervenant, $codeStatut);
                    $this->flashMessenger()->addSuccessMessage('La prise en charge s\'est déroulée avec succés et le dossier a été cloturé');
                    $this->getServiceIntervenant()->updateExportDate($intervenant);
                    //On met à jour le code intervenant si l'option est activée
                    if ($this->exportRhService->haveToSyncCode()) {
                        $this->getServiceIntervenant()->updateCode($intervenant, $matricule);
                    }
                    //On met à jour le code rh si l'option est activée
                    if ($this->exportRhService->haveToSyncCodeRh()) {
                        $this->getServiceIntervenant()->updateCodeRh($intervenant, $matricule);
                    }
                    //On met à jour le source code lors de la synchronisation
                    if ($this->exportRhService->haveToSyncSource()) {
                        $this->getServiceIntervenant()->updateSource($intervenant);
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('Problème prise en charge');
                }
            }
        } catch (\Exception $e) {

            $this->flashMessenger()->addErrorMessage($e->getMessage());
            return $this->redirect()->toRoute('intervenant/exporter', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'export-rh']]);

        }

        return $this->redirect()->toRoute('intervenant/exporter', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'export-rh']]);

    }



    public function exporterAction(): array
    {

        /* Initialisation */
        $intervenant        = $this->getServiceContext()->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantRh      = '';
        $form               = '';
        $nameConnecteur     = '';
        $affectationEnCours = '';
        $contratEnCours     = '';
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }
        /* Récupération du dossier de l'intervenant */
        $intervenantDossier = $this->getServiceDossier()->getByIntervenant($intervenant);
        /* Récupération de la validation du dossier si elle existe */
        $intervenantDossierValidation = $this->getServiceDossier()->getValidation($intervenant);
        $typeIntervenant              = $intervenant->getStatut()->getTypeIntervenant()->getCode();
        $renouvellement               = false;
        $priseEnCharge                = false;

        /**
         * Etape 1 : On cherche si l'intervenant est déjà dans le SI RH
         * Etape 2 : Si pas dans le SI RH alors c'est une prise en charge
         * Etape 3 : Si il est déjà dans le SI RH alors on regarde si il a une affectation en cours pour l'année en cours
         * Etape 4 : Si il a une affectation en cours alors on propose uniquement la mise à jour des données personnelles
         * Etape 5 : Si il n'a pas encore d'affectation on propose alors un renouvellement de l'intervenant
         *
         */
        try {
            $intervenantRh = $this->exportRhService->getIntervenantRh($intervenant);

            //On a trouvé un intervenant dans le SI RH
            if (!empty($intervenantRh)) {
                //On regarde si il a une affectation en cours pour l'année courante si oui alors on propose uniquement une synchronisation des données personnelles
                $listeAffectations = $this->exportRhService->getAffectationEnCoursIntervenantRh($intervenant);
                //On prend la dernière affectation connue dans SIHAM
                $affectationEnCours = end($listeAffectations);
                $listeContrats      = $this->exportRhService->getContratEnCoursIntervenantRh($intervenant);
                //On prend le dernier contrat connu dans SIHAM
                $contratEnCours = end($listeContrats);

                $renouvellement = true;
                if (!empty($listeAffectations)) {
                    $renouvellement = false;
                }
            } else {
                $priseEnCharge = true;
            }


            $nameConnecteur = $this->exportRhService->getConnecteurName();
            $form           = $this->getFormExportRh($intervenant);
            $form->bind($intervenantDossier);
            //On essaie de mettre l'affectation par défaut en comparant les libellés courts des structures (OSE/SIHAM)
            $form->setAffectationDefault($intervenant);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }


        return compact('typeIntervenant',
                       'intervenant',
                       'intervenantRh',
                       'intervenantDossier',
                       'intervenantDossierValidation',
                       'form',
                       'renouvellement',
                       'priseEnCharge',
                       'nameConnecteur',
                       'affectationEnCours',
                       'contratEnCours',
        );
    }



    public function renouvellementAction()
    {
        try {
            $intervenant = $this->getEvent()->getParam('intervenant');
            if ($this->getRequest()->isPost()) {
                if (!$intervenant) {
                    throw new \LogicException('Intervenant non précisé ou inexistant');
                }
                $posts           = $this->getRequest()->getPost();
                $missingArgument = 0;
                if (empty($posts['connecteurForm']['affectation'])) {
                    $this->flashMessenger()->addErrorMessage('Vous n\'avez pas choisi d\'affectation pour l\'agent');
                    $missingArgument++;
                }
                if (empty($posts['connecteurForm']['emploi'])) {
                    $this->flashMessenger()->addErrorMessage('Vous n\'avez pas choisi de type d\'emploi pour l\'agent');
                    $missingArgument++;
                }
                if (isset($posts['connecteurForm']['statut'])) {
                    $codeStatut = $posts['connecteurForm']['statut'];
                } else {
                    throw new \Exception("Le statut de prise en charge n'a pas été précisé");
                }

                if ($missingArgument == 0) {
                    $matricule = $this->exportRhService->renouvellementIntervenantRh($intervenant, $posts);
                    if ($matricule !== false) {
                        $this->exportRhService->cloreDossier($intervenant, $codeStatut);
                        $this->flashMessenger()->addSuccessMessage('Le renouvellement s\'est déroulé avec succés et le dossier a été cloturé');
                        $this->getServiceIntervenant()->updateExportDate($intervenant);
                        if ($this->exportRhService->haveToSyncCode()) {
                            $this->getServiceIntervenant()->updateCode($intervenant, $matricule);
                        }
                        if ($this->exportRhService->haveToSyncSource()) {
                            $this->getServiceIntervenant()->updateSource($intervenant);
                        }
                    } else {
                        $this->flashMessenger()->addErrorMessage('Un problème est survenu lors de la tentative de renouvellement de l\'intervenant');
                    }
                }
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
            return $this->redirect()->toRoute('intervenant/exporter', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'export-rh']]);
        }
        return $this->redirect()->toRoute('intervenant/exporter', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'export-rh']]);
    }



    public function synchroniserAction()
    {
        try {
            $intervenant = $this->getEvent()->getParam('intervenant');
            if ($this->getRequest()->isPost()) {
                if (!$intervenant) {
                    throw new \LogicException('Intervenant non précisé ou inexistant');
                }

                $posts  = $this->getRequest()->getPost();
                $result = $this->exportRhService->synchroniserDonneesPersonnellesIntervenantRh($intervenant, $posts);
                if ($result !== false) {
                    $this->flashMessenger()->addSuccessMessage('Les données personnelles ont bien été synchronisées');
                } else {
                    $this->flashMessenger()->addErrorMessage('Un problème est survenu lors de la synchronisation des données personnelles');
                }
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/exporter', ['intervenant' => $intervenant->getId()], ['query' => ['tab' => 'export-rh']]);

    }

}
