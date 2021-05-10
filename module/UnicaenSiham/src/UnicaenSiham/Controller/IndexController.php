<?php

namespace UnicaenSiham\Controller;


use UnicaenSiham\Exception\SihamException;
use UnicaenSiham\Service\Siham;
use UnicaenSiham\Service\SihamClient;
use UnicaenSiham\Service\Traits\SihamAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;


class IndexController extends AbstractActionController
{

    protected $siham;



    public function __construct(Siham $siham)
    {
        $this->siham = $siham;
    }



    public function indexAction(): array
    {
        $params = [
            'nomUsuel' => '',
            'prenom'   => '',
        ];

        $agents = [];
        try {

            if ($this->getRequest()->isPost()) {

                $params['nomUsuel'] = $this->getRequest()->getPost('nomUsuel');
                $params['prenom']   = $this->getRequest()->getPost('prenom');
                $agents             = $this->siham->rechercherAgent($params);
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return compact('agents');
    }



    public function voirAction(): array
    {
        $matricule = $this->params()->fromRoute('matricule');
        $agent     = [];
        try {
            if ($this->getRequest()->isPost()) {
                //traitemetn de la modification des données personnelles
                $params   = $this->getRequest()->getPost();
                $paramsWS = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                    'nomVoie'           => $params->nomVoieAdresse,
                    '',
                ];
                if (empty($params['dateDebut'])) {
                    // $result = $this->siham->ajouterAdresseAgent($paramsWS);
                } else {
                    //$result = $this->siham->modifierAdresseAgent($paramsWS);
                }
                //gestion des numéros de téléphone
                $paramsWS = [
                    'matricule' => $params->matricule,
                    'dateDebut' => $params->telFixeProDateDebut,
                    'numero'    => $params->telFixePro,

                ];

                $result = $this->siham->modifierTelephoneAgent($paramsWS, Siham::SIHAM_CODE_TYPOLOGIE_FIXE_PRO);

                $this->flashMessenger()->addSuccessMessage('Modification effectuée avec succés');
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } finally {
            try {
                $agent = $this->siham->recupererDonneesPersonnellesAgent(['listeMatricules' => [$matricule]]);
            } catch (SihamException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return compact('agent');
    }



    public function modifierAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
        try {
            if ($this->getRequest()->isPost()) {
                //traitemetn de la modification des données personnelles
                $params = $this->getRequest()->getPost();
                $params = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                ];

                if (empty($params['dateDebut'])) {
                    $result = $this->siham->ajouterAdresseAgent($params);
                } else {
                    $result = $this->siham->modifierAdresseAgent($params);
                }
                $this->flashMessenger()->addSuccessMessage('Modification effectuée avec succés');
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        } finally {
            try {
                $agent = $this->siham->recupererDonneesPersonnellesAgent(['listeMatricules' => [$matricule]]);
            } catch (SihamException $e) {
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $matricule]);
    }



    public function historiserAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
        $dateFin   = date("Y-m-d H:i:s");
        $agent     = [];

        try {
            $params = [
                'matricule' => $matricule,
                //'dateDebut'         => $params->dateDebut,
                'dateFin'   => $dateFin,
            ];

            $result = $this->siham->historiserAdresseAgent($params);
            $this->flashMessenger()->addSuccessMessage("Adresse principale historisée avec succés");
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('siham/voir', ['matricule' => $matricule]);
    }



    public function ajouterAdresseAgentAction()
    {
        $matricule = $this->params()->fromRoute('matricule');
    }



    public function voirNomenclatureAction()
    {
        $nomenclature = $this->params()->fromRoute('nomenclature');
        $result       = $this->siham->recupererNomenclatureRH(['listeNomenclatures' => [$nomenclature]]);

        return compact('result');
    }



    public function saveAction(): array
    {
        return [];
    }
}
