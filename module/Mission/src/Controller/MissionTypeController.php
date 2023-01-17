<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Form\MissionTypeFormAwareTrait;
use Mission\Form\MissionTypeValeurFormAwareTrait;
use Mission\Service\MissionTypeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of MissionTauxController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeController extends AbstractController
{
    use MissionTypeServiceAwareTrait;
    use ContextServiceAwareTrait;
//    use MissionTypeFormAwareTrait;

    public function indexAction()
    {

        $typeMissions = $this->getServiceMissionType()->getTypes();

        return compact('typeMissions');
    }



    public function saisirAction()
    {

        $type = $this->getEvent()->getParam('typeMission');
        $form     = $this->getFormMissionType();
        if (empty($type)) {
            $title    = "Création d'un nouveau type";
            $type = $this->getServiceMissionType()->newEntity();
        } else {
            $title = "Édition d'un type";
        }
        $form->bindRequestSave($type, $this->getRequest(), function () use ($type, $form) {
            $this->getServiceMissionTaux()->save($type);

            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title');
    }
    public function supprimerAction(): MessengerViewModel
    {
        $type = $this->getEvent()->getParam('typeMission');
        $this->getServiceMissionType()->delete($type, true);

        return new MessengerViewModel();
    }
}

