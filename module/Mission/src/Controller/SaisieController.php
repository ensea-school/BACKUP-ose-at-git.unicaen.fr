<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Form\MissionSuiviFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of SaisieController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SaisieController extends AbstractController
{
    use MissionServiceAwareTrait;
    use MissionFormAwareTrait;
    use ContextServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use MissionSuiviFormAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use PlafondProcessusAwareTrait;

    /**
     * Page d'index des missions
     *
     * @return array|\Laminas\View\Model\ViewModel
     */
    public function indexAction()
    {
        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $menu = new ViewModel();
            $menu->setTemplate('intervenant/intervenant/menu');

            return $menu;
        }

        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $canAddMission = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_EDITION));

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        return compact('intervenant', 'canAddMission', 'typeVolumeHoraire');
    }



    /**
     * Retourne les données pour une mission
     *
     * @return JsonModel
     */
    public function getAction(?Mission $mission = null)
    {
        if (!$mission) {
            /** @var Mission $mission */
            $mission = $this->getEvent()->getParam('mission');
        }

        // Vidage du cache d'exécution Doctrine pour être sûr de bien filter les données de la mission
        $this->em()->clear();

        $model = $this->getServiceMission()->data(['mission' => $mission]);
        $model->returnFirstItem();

        return $model;
    }



    /**
     * Retourne la liste des missions
     *
     * @return JsonModel
     */
    public function listeAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $model = $this->getServiceMission()->data(['intervenant' => $intervenant]);

        return $model;
    }



    /**
     * Ajoute une nouvelle mission (form)
     *
     * @return ViewModel
     */
    public function ajoutAction(): ViewModel
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $mission = $this->getServiceMission()->newEntity();
        $mission->setEntityManager($this->em());
        $mission->setIntervenant($intervenant);

        $canAutoValidate = $this->isAllowed($mission, Privileges::MISSION_AUTOVALIDATION);

        if ($canAutoValidate) $mission->setAutoValidation(true);

        return $this->saisieAction($mission);
    }



    /**
     * Modifie une mission (form)
     *
     * @param Mission|null $mission
     *
     * @return ViewModel
     */
    public function saisieAction(?Mission $mission = null): ViewModel
    {
        if (!$mission) {
            /** @var Mission $mission */
            $mission = $this->getEvent()->getParam('mission');

            $title = 'Modification d\'une mission';
        } else {
            $title = 'Ajout d\'une mission';
        }

        $form = $this->getFormMission();

        if ($mission->isValide()) {
            $form->editValide();
        }

        if ($this->getServiceContext()->getStructure()) {
            if (!$mission->getStructure()) {
                $mission->setStructure($this->getServiceContext()->getStructure());
            }
        }

        $hDeb = $mission->getHeures();
        $form->bindRequestSave($mission, $this->getRequest(), function ($mission) use ($hDeb) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceMission()->save($mission);
                $hFin = $mission->getHeures();
                if ($this->getProcessusPlafond()->endTransaction($mission, $typeVolumeHoraire, $hFin <= $hDeb)) {
                    $this->flashMessenger()->addSuccessMessage('Mission bien enregistrée');
                }
                $this->updateTableauxBord($mission);

            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
                $this->em()->rollback();
            }
        });
        // on passe le data-id pour pouvoir le récupérer dans la vue et mettre à jour la liste
        $form->setAttribute('data-id', $mission->getId());

        $vm = new ViewModel();
        $vm->setTemplate('mission/saisie/saisie');
        $vm->setVariables(compact('form', 'title', 'mission'));

        return $vm;
    }



    public function supprimerAction()
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        if ($mission->canSupprimer()) {
            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceMission()->delete($mission);
                //On historise les volumes horaires de la mission
                $volumesHoraires = $mission->getVolumesHorairesPrevus();
                foreach ($volumesHoraires as $volumesHoraire) {
                    $this->getServiceMission()->deleteVolumeHoraire($volumesHoraire);
                }
                $this->updateTableauxBord($mission);
                $this->flashMessenger()->addSuccessMessage("Mission supprimée avec succès.");
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
            $this->getProcessusPlafond()->endTransaction($mission, $typeVolumeHoraire, true);
        } else {
            $this->flashMessenger()->addErrorMessage('Vous n\'avez pas la possibilité de supprimer cette mission : elle a déjà été validée ou a fait l\'objet d\'un contrat');
        }

        return new AxiosModel([]);
    }



    public function validerAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        if ($mission->isValide()) {
            $this->flashMessenger()->addInfoMessage('La mission est déjà validée');
        } else {
            $this->getServiceValidation()->validerMission($mission);
            $this->getServiceMission()->save($mission);
            $this->updateTableauxBord($mission);
            $perimetre = $this->getProcessusPlafond()->getServicePlafond()->getPerimetre('structure');
            $this->getProcessusPlafond()->getServicePlafond()->calculer($perimetre);

            $this->flashMessenger()->addSuccessMessage('Mission validée');
        }

        return $this->getAction($mission);
    }



    public function devaliderAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        $validation = $mission->getValidation();
        if ($validation) {
            $mission->setAutoValidation(false);
            $mission->removeValidation($validation);
            $this->getServiceValidation()->delete($validation);
            $this->updateTableauxBord($mission);
            $perimetre = $this->getProcessusPlafond()->getServicePlafond()->getPerimetre('structure');
            $this->getProcessusPlafond()->getServicePlafond()->calculer($perimetre);
            $this->flashMessenger()->addSuccessMessage("Validation de la mission <strong>retirée</strong> avec succès.");
        } else {
            $this->flashMessenger()->addInfoMessage("La mission n'était pas validée");
        }


        return $this->getAction($mission);
    }



    public function volumeHoraireSupprimerAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();

        $this->getProcessusPlafond()->beginTransaction();
        try {
            $this->getServiceMission()->deleteVolumeHoraire($volumeHoraireMission);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage("Volume horaire supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }
        $this->getProcessusPlafond()->endTransaction($volumeHoraireMission, $typeVolumeHoraire, true);

        return $this->getAction($volumeHoraireMission->getMission());
    }



    public function volumeHoraireValiderAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        if ($volumeHoraireMission->isValide()) {
            $this->flashMessenger()->addInfoMessage('Ce volume horaire est déjà validé');
        } else {
            $this->getServiceValidation()->validerVolumeHoraireMission($volumeHoraireMission);
            $this->getServiceMission()->saveVolumeHoraire($volumeHoraireMission);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage('Volume horaire validé');
        }

        return $this->getAction($volumeHoraireMission->getMission());
    }



    public function volumeHoraireDevaliderAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        $validation = $volumeHoraireMission->getValidation();
        if ($validation) {
            $volumeHoraireMission->setAutoValidation(false);
            $volumeHoraireMission->removeValidation($validation);
            $this->getServiceValidation()->delete($validation);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage("Validation du volume horaire <strong>retirée</strong> avec succès.");
        } else {
            $this->flashMessenger()->addInfoMessage("Ce volume horaire n'était pas validé");
        }

        return $this->getAction($volumeHoraireMission->getMission());
    }



    private function updateTableauxBord(Mission $mission)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
                                                              TblProvider::MISSION,
                                                              TblProvider::CONTRAT,
                                                          ], $mission->getIntervenant());
    }
}