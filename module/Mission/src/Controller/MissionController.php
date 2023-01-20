<?php

namespace Mission\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Laminas\View\Model\JsonModel;
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
        foreach ($missions as $k => $mission) {
            $missions[$k] = $this->getServiceMission()->missionWs($mission);
        }

        return $this->axios()->send($missions);
    }



    /**
     * Retourne les données pour une mission
     *
     * @return JsonModel
     */
    public function missionAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        return $this->axios()->send($this->getServiceMission()->missionWs($mission));
    }



    /**
     * Formulaire de saisie
     *
     * @return array
     */
    public function saisieAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        if ($mission) {
            $title = 'Modification d\'une mission';
        } else {
            $title   = 'Ajout d\'une mission';
            $mission = $this->getServiceMission()->newEntity();
        }

        $form = $this->getFormMission();
        $form->remove('structure');
        $form->bindRequestSave($mission, $this->getRequest(), function ($mission) {
            $this->getServiceMission()->save($mission);
        });

        return compact('form', 'title', 'mission');
    }



    public function supprimerAction()
    {
        /** @todo */
    }



    public function validerAction()
    {

    }



    public function devaliderAction()
    {

    }
}