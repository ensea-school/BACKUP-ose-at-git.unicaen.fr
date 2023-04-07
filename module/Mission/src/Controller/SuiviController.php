<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\Intervenant;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Form\MissionSuiviFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\Axios\AxiosExtractor;
use UnicaenVue\View\Model\AxiosModel;


/**
 * Description of SuiviController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SuiviController extends AbstractController
{
    use MissionServiceAwareTrait;
    use MissionFormAwareTrait;
    use ContextServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use MissionSuiviFormAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;

    public function indexAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        return compact('intervenant');
    }



    public function listeAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $query = $this->getServiceMission()->suivi(['intervenant' => $intervenant]);

        return new AxiosModel($query);
    }



    /**
     * Ajout un nouveau suivi de mission (form)
     *
     * @return ViewModel
     */
    public function ajoutAction(): ViewModel
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $date = $this->params()->fromRoute('date');

        $volumeHoraireMission = new VolumeHoraireMission();
        $volumeHoraireMission->setTypeVolumeHoraire($this->getServiceTypeVolumeHoraire()->getRealise());
        $volumeHoraireMission->setDate($date);

        return $this->saisieAction($intervenant, $volumeHoraireMission);
    }



    /**
     * Modifie un suivi de mission (form)
     *
     * @return ViewModel
     */
    public function modifierAction(): ViewModel
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        return $this->saisieAction($volumeHoraireMission->getMission()->getIntervenant(), $volumeHoraireMission);
    }



    protected function saisieAction(Intervenant $intervenant, VolumeHoraireMission $volumeHoraireMission)
    {
        if ($volumeHoraireMission->getId()) {
            $title = 'Modification d\'un suivi de mission';
        } else {
            $title = 'Ajout d\'un suivi de mission';
        }

        $form = $this->getFormMissionSuivi();
        $form->setIntervenant($intervenant);
        $form->date = $volumeHoraireMission->getHoraireDebut();
        $form->build();

        $form->bindRequestSave($volumeHoraireMission, $this->getRequest(), function ($vhm) {
            $this->getServiceMission()->saveVolumeHoraire($vhm);
            $this->updateTableauxBord($vhm->getMission());
            $this->flashMessenger()->addSuccessMessage('Suivi bien enregistré');
        });
        // on passe l'id pour pouvoir le récupérer dans la vue et mettre à jour la liste
        $form->setAttribute('data-id', $volumeHoraireMission->getId());

        $vm = new ViewModel();
        $vm->setTemplate('mission/suivi/saisie');
        $vm->setVariables(compact('form', 'title'));

        return $vm;
    }



    public function supprimerAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        try {
            $this->getServiceMission()->deleteVolumeHoraire($volumeHoraireMission);
            $this->flashMessenger()->addSuccessMessage('Le suivi a bien été supprimé');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('Une erreur s\'est produite lors de la suppression du suivi : ' . $e->getMessage());
        }

        return new AxiosModel([]);
    }



    public function validerAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        if ($volumeHoraireMission->isValide()) {
            $this->flashMessenger()->addInfoMessage('Ce suivi a déjà été validé');
        } else {
            $this->getServiceValidation()->validerVolumeHoraireMission($volumeHoraireMission);
            $this->getServiceMission()->saveVolumeHoraire($volumeHoraireMission);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage('Suivi validé');
        }

        return new AxiosModel([]);
    }



    public function devaliderAction()
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        $validation = $volumeHoraireMission->getValidation();
        if ($validation) {
            $volumeHoraireMission->setAutoValidation(false);
            $volumeHoraireMission->removeValidation($validation);
            $this->getServiceValidation()->delete($validation);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage("Validation du suivi <strong>retirée</strong>.");
        } else {
            $this->flashMessenger()->addInfoMessage("Ce suivi n'était pas validé");
        }

        return new AxiosModel([]);
    }



    private function updateTableauxBord(Mission $mission)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'mission',
        ], $mission->getIntervenant());
    }

}