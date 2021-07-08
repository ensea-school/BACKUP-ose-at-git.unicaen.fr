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
        $intervenantRh                = $this->exportRhService->getIntervenantRh($intervenant);

        /*Scénario 1 : Intervenant non présent dans le SI RH*/
        $form = $this->getExportRhForm();


        /*Scénario 2 : Intervenant présent dans le SI RH donc uniquement mis à jour des données*/
        /*Scénario 3 : Intervenant présent dans le SI RH avec une affectation*/
        $nameConnecteur = $this->exportRhService->getConnecteurName();


        return compact('typeIntervenant',
            'intervenant',
            'intervenantRh',
            'intervenantDossier',
            'intervenantDossierValidation',
            'form',
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
