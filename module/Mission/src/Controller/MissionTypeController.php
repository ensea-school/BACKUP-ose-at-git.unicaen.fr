<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\CentreCoutTypeMission;
use Mission\Entity\Db\TypeMission;
use Mission\Form\MissionCentreCoutsTypeFormAwareTrait;
use Mission\Form\MissionTypeFormAwareTrait;
use Mission\Service\MissionTypeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Paiement\Service\CentreCoutServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of MissionTypeController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeController extends AbstractController
{
    use MissionTypeServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use ContextServiceAwareTrait;
    use MissionTypeFormAwareTrait;
    use MissionCentreCoutsTypeFormAwareTrait;

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


    public function visualiserAction()
    {

        $typeMission = $this->getEvent()->getParam('typeMission');
        $tab         = $this->params()->fromQuery('tab', 'fiche');


        return compact( 'typeMission', 'tab');
    }


    public function saisirAction()
    {

        $typeMission = $this->getEvent()->getParam('typeMission');
        $tab         = $this->params()->fromQuery('tab', 'edition');
        $form        = $this->getFormMissionType();
        if (empty($typeMission)) {
            $title       = "Création d'un nouveau type";
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



    public function CentreCoutsAction(): ViewModel
    {
        $title = 'Gestion des centres de coûts';
        /**
         * @var TypeMission $entity
         */
        $entity    = $this->getEvent()->getParam('typeMission');
        $canEditCC = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_EDITION_CENTRE_COUT_TYPE));

        if ($this->getRequest()->isPost()) {
            $centreCoutsId = $this->getRequest()->getPost()->get('centreCouts');
            $cenCoutsIds = $entity->getCentreCoutsIds();
            if(!in_array($centreCoutsId,$cenCoutsIds)){

                $centreCouts = $this->getServiceCentreCout()->get($centreCoutsId);

                $centreCoutTypeLinker = new CentreCoutTypeMission();
                $centreCoutTypeLinker->setTypeMission($entity);
                $centreCoutTypeLinker->setCentreCouts($centreCouts);
                $this->getServiceMissionType()->saveCentreCoutTypeLinker($centreCoutTypeLinker);

                $entity->addCentreCoutsLinker($centreCoutTypeLinker);
                $this->getServiceMissionType()->save($entity);
            }
        }


        $form = $this->getFormMissionCentreCoutsType();

        $centreCoutsLinkers = $entity->getCentreCoutsLinkers();
        $vm                 = new ViewModel();

        $vm->setVariables(compact('form', 'title', 'canEditCC', 'entity', 'centreCoutsLinkers'));

        return $vm;
    }

    public function CentreCoutsSupprimerAction(): \Laminas\Http\Response
    {
        /**
         * @var TypeMission $entity
         */
        $entity    = $this->getEvent()->getParam('typeMission');
        $centreCoutsLinker    = $this->getEvent()->getParam('centreCoutTypeMission');

        $this->getServiceMissionType()->removeCentreCoutLinker($centreCoutsLinker);


        return $this->redirect()->toRoute('missions-type/centre-couts', ['typeMission' => $entity->getId()]);
    }
}

