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
        $role        = $this->getServiceContext()->getSelectedIdentityRole();
        $intervenant = $role->getIntervenant() ?: $this->getEvent()->getParam('intervenant');

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

        $intervenantRh = $this->exportRhService->getIntervenantRh($intervenant);

        if ($intervenantRh) {
            //On a trouvé un intervenant dans le SI RH
            $affectationEnCours = $this->exportRhService->getAffectationEnCours($intervenant);
            //On regarde si il a une affectation en cours pour l'année courante si oui alors on propose uniquement une synchronisation des données personnelles
            $renouvellement = true;
            if (!empty($affectationEnCours)) {
                //Si non on propose un renouvellement de l'intervenant SI RH
                $renouvellement = false;
            }
        } else {
            $priseEnCharge = true;
        }

        $form = $this->getExportRhForm();


        $nameConnecteur = $this->exportRhService->getConnecteurName();


        return compact('typeIntervenant',
            'intervenant',
            'intervenantRh',
            'intervenantDossier',
            'intervenantDossierValidation',
            'form',
            'renouvellement',
            'priseEnCharge',
            'nameConnecteur');
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

}
