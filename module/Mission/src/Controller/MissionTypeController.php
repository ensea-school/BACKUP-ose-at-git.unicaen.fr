<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Mission\Entity\Db\TypeMission;
use Mission\Form\MissionTypeFormAwareTrait;
use Mission\Service\MissionTypeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of tauxRemuController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeController extends AbstractController
{
    use MissionTypeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use MissionTypeFormAwareTrait;

    public function indexAction()
    {
        $this->em()->getFilters()->enable('annee')->init([
            TypeMission::class,
        ]);
        $this->em()->getFilters()->enable('historique')->init([
            TypeMission::class,
        ]);
        $missionsType = $this->getServiceMissionType()->getTypes();

        return compact('missionsType');
    }



    public function saisirAction()
    {

        $typeMission = $this->getEvent()->getParam('typeMission');
        $tab = $this->params()->fromQuery('tab', 'fiche');
        $form = $this->getFormMissionType();
        if (empty($typeMission)) {
            $title = "Création d'un nouveau type";
            $typeMission = $this->getServiceMissionType()->newEntity();
        } else {
            $title = "Édition d'un type";
        }
        $form->bindRequestSave($typeMission, $this->getRequest(), function () use ($typeMission, $form) {
            $this->getServiceMissionType()->save($typeMission);

            $this->flashMessenger()->addSuccessMessage(
                "Ajout réussi"
            );
        });

        return compact('form', 'title', 'tab', 'typeMission');
    }



    public function supprimerAction(): MessengerViewModel
    {
        $type = $this->getEvent()->getParam('typeMission');
        $this->getServiceMissionType()->delete($type, true);

        return new MessengerViewModel();
    }
}

