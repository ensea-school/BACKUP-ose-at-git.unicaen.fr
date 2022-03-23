<?php

namespace Application\Controller;


use Application\Entity\Db\DossierAutre;
use Application\Form\Intervenant\Traits\AutresFormAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\DossierAutreTypeServiceAwareTrait;

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
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title', 'dossierAutre');
    }
}
