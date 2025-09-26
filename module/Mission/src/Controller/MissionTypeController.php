<?php

namespace Mission\Controller;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Laminas\View\Model\ViewModel;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Mission\Entity\Db\CentreCoutTypeMission;
use Mission\Entity\Db\TypeMission;
use Mission\Form\MissionCentreCoutsTypeFormAwareTrait;
use Mission\Form\MissionTypeFormAwareTrait;
use Mission\Service\MissionTypeServiceAwareTrait;
use Paiement\Service\CentreCoutServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;

/**
 * Description of MissionTypeController
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeController extends AbstractController
{
    use StructureServiceAwareTrait;
    use MissionTypeServiceAwareTrait;
    use CentreCoutServiceAwareTrait;
    use ContextServiceAwareTrait;
    use MissionTypeFormAwareTrait;
    use MissionCentreCoutsTypeFormAwareTrait;
    use ParametresServiceAwareTrait;

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


        return compact('typeMission', 'tab');
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
         * @var TypeMission $typeMission
         */
        $typeMission = $this->getEvent()->getParam('typeMission');
        $canEditCC   = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_EDITION_CENTRE_COUT_TYPE));

        if ($this->getRequest()->isPost()) {
            $centreCoutsId = $this->getRequest()->getPost()->get('centreCouts');
            $structureId   = $this->getRequest()->getPost()->get('structure');
            if ($centreCoutsId != null) {
                $centreCouts   = $this->getServiceCentreCout()->get($centreCoutsId);
                $structureCC   = $this->getServiceStructure()->get($structureId);
                $anneeCourante = (int)$this->getServiceParametres()->get('annee');

                $this->getServiceMissionType()->addCentreCoutTypeMission($centreCouts, $structureCC, $typeMission, $anneeCourante);
            }
        }

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($role->getStructure()) {
            $structures[] = $role->getStructure();
        } else {
            $filter     = function (Structure $structure) {
                return !$structure->estHistorise();
            };
            $structures = array_filter($this->getServiceStructure()->getList(), $filter);
        }
        $forms = [];
        foreach ($structures as $structure) {
            $form = $this->getFormMissionCentreCoutsType();
            $form->get('structure')->setValue($structure->getId());

            if (empty($structure->getCentreCout()->toArray())) {
                $form->get('centreCouts')->setEmptyOption('Aucun centre de coûts disponible');
                $form->remove('submit');
            } else {
                $form->setValueOptions('centreCouts', $structure->getCentreCout()->toArray());
            }
            $forms[$structure->getId()] = $form;
        }
        $centreCoutsTypeMission       = $typeMission->getCentreCoutsTypeMission();
        $centreCoutsTypeMissionStruct = [];
        foreach ($centreCoutsTypeMission as $ctm) {
            /** @var CentreCoutTypeMission $ctm */
            $centreCoutsTypeMissionStruct[$ctm->getStructure()->getId()] = $ctm;
        }
        $vm = new ViewModel();

        $vm->setVariables(compact('forms', 'structures', 'title', 'canEditCC', 'typeMission', 'centreCoutsTypeMissionStruct'));

        return $vm;
    }



    public function CentreCoutsSupprimerAction(): \Laminas\Http\Response
    {
        /**
         * @var TypeMission $entity
         */
        $entity                = $this->getEvent()->getParam('typeMission');
        $centreCoutTypeMission = $this->getEvent()->getParam('centreCoutTypeMission');
        $anneeCourante = (int) $this->getServiceParametres()->get('annee');

        $this->getServiceMissionType()->removeCentreCoutLinker($centreCoutTypeMission, $anneeCourante);


        return $this->redirect()->toRoute('missions-type/centre-couts', ['typeMission' => $entity->getId()]);
    }


}

