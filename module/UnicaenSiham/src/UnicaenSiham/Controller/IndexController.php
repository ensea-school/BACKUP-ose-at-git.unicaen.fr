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
                $params = $this->getRequest()->getPost();
                $params = [
                    'matricule'         => $params->matricule,
                    'dateDebut'         => $params->dateDebut,
                    'complementAdresse' => $params->complementAdresse,
                    'natureVoie'        => $params->natureVoie,
                    'codePostal'        => $params->codePostal,
                    'ville'             => $params->ville,
                ];
                $result = $this->siham->modificationAdresseAgent($params);
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
