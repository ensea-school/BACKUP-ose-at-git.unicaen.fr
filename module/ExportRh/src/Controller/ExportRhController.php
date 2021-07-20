<?php

namespace ExportRh\Controller;


use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use ExportRh\Form\ExportRhForm;
use ExportRh\Form\Traits\ExportRhFormAwareTrait;
use ExportRh\Service\ExportRhService;
use ExportRh\Service\ExportRhServiceAwareTrait;
use UnicaenSiham\Exception\SihamException;

class ExportRhController extends AbstractController
{

    use ExportRhServiceAwareTrait;
    use ContextServiceAwareTrait;
    use DossierServiceAwareTrait;
    use ExportRhFormAwareTrait;

    /**
     * @var ExportRhService $exportRhService
     */
    protected $exportRhService;



    public function __construct(ExportRhService $exportRhService)
    {
        $this->exportRhService = $exportRhService;
    }



    public function indexAction()
    {
        return [];
    }



    public function chercherIntervenantRhAction(): array
    {
        $connecteurRh = $this->getExportRhService();

        $params = [
            'nomUsuel' => '',
            'prenom'   => '',
        ];

        $listIntervenantRh = [];

        try {

            if ($this->getRequest()->isPost()) {

                $nomUsuel          = $this->getRequest()->getPost('nomUsuel');
                $prenom            = $this->getRequest()->getPost('prenom');
                $insee             = $this->getRequest()->getPost('insee');
                $listIntervenantRh = $connecteurRh->getListIntervenantRh($nomUsuel, $prenom, $insee);
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return compact('listIntervenantRh');
    }



    public function exporterAction()
    {

        /* Initialisation */
        $role               = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant        = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');
        $intervenantRh      = '';
        $form               = '';
        $nameConnecteur     = '';
        $affectationEnCours = '';

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
         */
        try {
            $intervenantRh = $this->exportRhService->getIntervenantRh($intervenant);
            //On a trouvé un intervenant dans le SI RH
            if (!empty($intervenantRh)) {
                //On regarde si il a une affectation en cours pour l'année courante si oui alors on propose uniquement une synchronisation des données personnelles
                $affectationEnCours = current($this->exportRhService->getAffectationEnCoursIntervenantRh($intervenant));

                $renouvellement = true;
                if (!empty($affectationEnCours)) {
                    $renouvellement = false;
                }
            } else {
                $priseEnCharge = true;
            }


            $nameConnecteur = $this->exportRhService->getConnecteurName();
            $form           = $this->getExportRhForm($intervenant);
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
            'affectationEnCours');
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


                $result = $this->exportRhService->priseEnChargeIntrervenantRh($intervenant, $posts);
                if ($result !== false) {
                    $this->flashMessenger()->addSuccessMessage('succes matricule : ' . $result);
                } else {
                    $this->flashMessenger()->addErrorMessage('Probleme prise en charge');
                }
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/exporter', [], [], true);
    }



    public function renouvellementAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $intervenant = $this->getEvent()->getParam('intervenant');
                if (!$intervenant) {
                    throw new \LogicException('Intervenant non précisé ou inexistant');
                }

                $posts  = $this->getRequest()->getPost();
                $result = $this->exportRhService->renouvellementIntervenantRh($intervenant, $posts);
                if ($result !== false) {
                    $this->flashMessenger()->addSuccessMessage('Le renouvellement s\'est déroulé avec succés');
                } else {
                    $this->flashMessenger()->addErrorMessage('Un problème est survenu lors de la tentative de renouvellement de l\'intervenant');
                }
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }


        return $this->redirect()->toRoute('intervenant/exporter', [], [], true);
    }



    public function synchroniserAction()
    {
        try {
            if ($this->getRequest()->isPost()) {
                $intervenant = $this->getEvent()->getParam('intervenant');
                if (!$intervenant) {
                    throw new \LogicException('Intervenant non précisé ou inexistant');
                }

                $posts  = $this->getRequest()->getPost();
                $result = $this->exportRhService->synchroniserDonneesPersonnelles($intervenant, $posts);
                if ($result !== false) {
                    $this->flashMessenger()->addSuccessMessage('Les données personnelles ont bien été synchronisé');
                } else {
                    $this->flashMessenger()->addErrorMessage('Un problème est survenu lors de la synchronisation des données personnelles');
                }
            }
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('intervenant/exporter', [], [], true);
    }
}
