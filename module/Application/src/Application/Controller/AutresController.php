<?php

namespace Application\Controller;


use Application\Entity\Db\DossierAutre;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\DossierAutreTypeServiceAwareTrait;

class AutresController extends AbstractController
{

    use DossierAutreServiceAwareTrait;
    use DossierAutreTypeServiceAwareTrait;

    public function indexAction()
    {
        $dossierAutreListe = $this->getServiceDossierAutre()->getList();

        return compact('dossierAutreListe');
    }



    public function saisieAction()
    {
        $dossierAutre = $this->getEvent()->getParam('dossierAutre');
        $form         = $this->getAutresForm();
        $title        = 'Édition d\'un type de ressource';

        $form->bindRequestSave($dossierAutre, $this->getRequest(), function (DossierAutre $autre) {
            try {
                $this->getServiceDossierAutre()->save($autre);
                $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('form', 'title');
    }
}
