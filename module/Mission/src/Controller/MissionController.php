<?php

namespace Mission\Controller;

use Application\Constants;
use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextServiceAwareTrait;
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

        return $this->saisieAction($mission);
    }



    /**
     * Formulaire de saisie
     *
     * @return array
     */
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


        $vm = new ViewModel();
        $vm->setTemplate('mission/saisie');
        $vm->setVariables(compact('form', 'title', 'mission'));

        return $vm;
    }



    public function supprimerAction()
    {
        /** @var Mission $mission */
        $mission = $this->getEvent()->getParam('mission');

        try {
            //$this->getServiceMission()->delete($mission);
            $this->flashMessenger()->addSuccessMessage("Mission supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return $this->axios()->send([]);
    }



    public function validerAction()
    {

    }



    public function devaliderAction()
    {

    }
}