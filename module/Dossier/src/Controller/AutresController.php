<?php

namespace Dossier\Controller;


use Application\Controller\AbstractController;
use Dossier\Entity\Db\DossierAutre;
use Dossier\Form\Traits\AutresFormAwareTrait;
use Dossier\Service\Traits\DossierAutreServiceAwareTrait;
use Dossier\Service\Traits\DossierAutreTypeServiceAwareTrait;

class AutresController extends AbstractController
{

    use DossierAutreServiceAwareTrait;
    use DossierAutreTypeServiceAwareTrait;
    use AutresFormAwareTrait;

    public function indexAction()
    {
        $dossierAutreListe = $this->getServiceDossierAutre()->getList();

        return compact('dossierAutreListe');
    }



    public function saisieAction()
    {
        $dossierAutre = $this->getEvent()->getParam('dossierAutre');
        $form         = $this->getFormIntervenantAutres();
        $title        = 'Édition champs autre';

        $form->bindRequestSave($dossierAutre, $this->getRequest(), function (DossierAutre $autre) {
            try {
                $this->getServiceDossierAutre()->save($autre);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');

                return $this->redirect()->toRoute('autres-infos');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title', 'dossierAutre');
    }
}
