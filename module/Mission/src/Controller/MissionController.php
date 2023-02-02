<?php

namespace Mission\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;


/**
 * Description of MissionController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionController extends AbstractController
{
    use MissionServiceAwareTrait;
    use MissionFormAwareTrait;
    use ContextServiceAwareTrait;
    use ValidationServiceAwareTrait;


    /**
     * Page d'index des missions
     *
     * @return array|\Laminas\View\Model\ViewModel
     */
    public function indexAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $canAddMission = true;

        return compact('intervenant', 'canAddMission');
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

        if ($this->getServiceContext()->getStructure()) {
            $form->remove('structure');
        }
        $form->bindRequestSave($mission, $this->getRequest(), function ($mission) {
            $this->getServiceMission()->save($mission);
            $this->flashMessenger()->addSuccessMessage('Mission bien enregistrée');
        });
        // on passe le data-id pour pouvoir le récupérer dans la vue et mettre à jour la liste
        $form->setAttribute('data-id', $mission->getId());

        $vm = new ViewModel();
        $vm->setTemplate('mission/saisie');
        $vm->setVariables(compact('form', 'title', 'mission'));

        return $vm;
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

        $query = $this->getServiceMission()->query(['intervenant' => $intervenant]);

        return $this->axios()->send($query);
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

        $query = $this->getServiceMission()->query(['mission' => $mission]);

        return $this->axios()->send($this->axios()::extract($query)[0]);
    }



    public function supprimerAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        $this->getServiceMission()->delete($mission);
        $this->flashMessenger()->addSuccessMessage("Mission supprimée avec succès.");

        return $this->axios()->send([]);
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

        $this->getServiceMission()->deleteVolumeHoraire($volumeHoraireMission);
        $this->flashMessenger()->addSuccessMessage("Volume horaire supprimé avec succès.");

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
            $this->flashMessenger()->addSuccessMessage("Validation du volume horaire <strong>retirée</strong> avec succès.");
        } else {
            $this->flashMessenger()->addInfoMessage("Ce volume horaire n'était pas validé");
        }

        return $this->getAction($volumeHoraireMission->getMission());
    }
}