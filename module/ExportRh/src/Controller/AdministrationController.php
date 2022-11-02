<?php

namespace ExportRh\Controller;

use Application\Controller\AbstractController;
use ExportRh\Service\ExportRhServiceAwareTrait;
use UnicaenSiham\Exception\SihamException;


class AdministrationController extends AbstractController
{

    use ExportRhServiceAwareTrait;

    public function __construct()
    {

    }



    public function indexAction()
    {
        $erhs = $this->getServiceExportRh();

        $intervenantParams = $erhs->getIntervenantRHExportParams();
        $champs            = $erhs->getIntervenantRHParamsDescription();

        return compact('intervenantParams', 'champs');
    }



    public function chercherIntervenantRhAction(): array
    {
        $connecteurRh = $this->getServiceExportRh();

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

}
