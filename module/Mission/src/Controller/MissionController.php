<?php

namespace Mission\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;


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
     * Retourne la liste des missions
     *
     * @return JsonModel
     */
    public function listeAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $missions = $this->getServiceMission()->missionsByIntervenant($intervenant);
        $liste    = [];
        foreach ($missions as $mission) {
            $liste[$mission->getId()] = $this->getServiceMission()->missionWs($mission);
        }

        return $this->axios()->send($liste);
    }



    /**
     * Retourne les données pour une mission
     *
     * @return JsonModel
     */
    public function getAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        return $this->axios()->send($this->getServiceMission()->missionWs($mission));
    }



    public function ajoutAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $mission = $this->getServiceMission()->newEntity();
        $mission->setIntervenant($intervenant);

        /* pour les tests */
        $mission->setDateDebut(new \DateTime());
        $mission->setDateFin(new \DateTime());

        return $this->saisieAction($mission);
    }



    public function saisieAction(?Mission $mission = null)
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
        });
        // on passe le data-id pour pouvoir le récupérer dans la vue et mettre à jour la liste
        $form->setAttribute('data-id', $mission->getId());
        $form->get('heures')->setValue('8');
        $vm = new ViewModel();
        $vm->setTemplate('mission/saisie');
        $vm->setVariables(compact('form', 'title', 'mission'));

        return $vm;
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

        $this->getServiceValidation()->validerMission($mission);
        $this->getServiceMission()->save($mission);

        $this->flashMessenger()->addSuccessMessage('Mission validée');

        return $this->getAction();
    }



    public function devaliderAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        $validation = $mission->getValidation();
        if ($validation) {
            $mission->removeValidation($validation);
            $this->getServiceValidation()->delete($validation);
            $this->flashMessenger()->addSuccessMessage("Validation de la mission <strong>retirée</strong> avec succès.");
        } else {
            $this->flashMessenger()->addInfoMessage("La mission n'était pas validée");
        }

        return $this->getAction();
    }
}