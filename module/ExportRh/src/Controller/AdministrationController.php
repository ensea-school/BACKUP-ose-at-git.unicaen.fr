<?php

namespace ExportRh\Controller;

use Application\Controller\AbstractController;
use ExportRh\Service\ExportRhServiceAwareTrait;


class AdministrationController extends AbstractController
{

    use ExportRhServiceAwareTrait;

    public function __construct()
    {

    }



    public function indexAction()
    {
        $erhs = $this->getExportRhService();

        $intervenantParams = $erhs->getIntervenantRHExportParams();
        $champs            = $erhs->getIntervenantRHParamsDescription();

        return compact('intervenantParams', 'champs');
    }



    public function chercherIntervenantRhAction(): array
    {
        $connecteurRh = $this->getExportRhService();
        $connecteurRh->getIntervenantRh([]);

        $params = [
            'nomUsuel' => '',
            'prenom'   => '',
        ];

        $agents = [];
        try {

            if ($this->getRequest()->isPost()) {

                $params['nomUsuel'] = $this->getRequest()->getPost('nomUsuel');
                $params['prenom']   = $this->getRequest()->getPost('prenom');
                //$agents             = $this->siham->rechercherAgent($params);
            }
        } catch (SihamException $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return compact('agents');
    }

}
