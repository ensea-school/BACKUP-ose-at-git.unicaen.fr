<?php

namespace Mission\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Application\Provider\Tbl\TblProvider;
use Intervenant\Entity\Db\Intervenant;
use Laminas\View\Model\ViewModel;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Mission\Form\MissionFormAwareTrait;
use Mission\Form\MissionSuiviFormAwareTrait;
use Mission\Service\MissionServiceAwareTrait;
use Plafond\Processus\PlafondProcessusAwareTrait;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\ValidationServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of SuiviController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SuiviController extends AbstractController
{
    use MissionServiceAwareTrait;
    use MissionFormAwareTrait;
    use ValidationServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use MissionSuiviFormAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use PlafondProcessusAwareTrait;

    public function indexAction ()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $canAddMission = $this->isAllowed(Privileges::getResourceId(Privileges::MISSION_EDITION_REALISE));

        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();

        return compact('intervenant', 'canAddMission', 'typeVolumeHoraire');
    }



    public function listeAction ()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $parameters = [
            'typeVolumeHoraireRealise' => TypeVolumeHoraire::CODE_REALISE,
            'intervenant'              => $intervenant,
        ];

        $dql = "
        SELECT
            vhm, m
        FROM
            " . VolumeHoraireMission::class . " vhm
            JOIN vhm.typeVolumeHoraire tvh WITH tvh.code = :typeVolumeHoraireRealise
            JOIN vhm.mission m
        WHERE
            vhm.histoDestruction IS NULL
            AND m.intervenant = :intervenant
        ";

        $query = $this->em()->createQuery($dql)->setParameters($parameters);

        $properties = [
            'id',
            ['mission', ['id', 'libelleCourt', 'libelleMission']],
            'date',
            'heureDebut',
            'heureFin',
            'heures',
            'formation',
            'description',
            'valide',
            'validation',
            'canEdit',
            'canValider',
            'canDevalider',
            'canSupprimer',
        ];

        $triggers = [
            '/' => function (VolumeHoraireMission $original, array $extracted) {
                $extracted['canEdit']      = $this->isAllowed($original, Privileges::MISSION_EDITION_REALISE);
                $extracted['canValider']   = $this->isAllowed($original, Privileges::MISSION_VALIDATION_REALISE);
                $extracted['canDevalider'] = $this->isAllowed($original, Privileges::MISSION_DEVALIDATION_REALISE);
                $extracted['canSupprimer'] = $extracted['canSupprimer'] && $this->isAllowed($original, Privileges::MISSION_EDITION_REALISE);

                return $extracted;
            },
        ];

        return new AxiosModel($query, $properties, $triggers);
    }



    /**
     * Ajout un nouveau suivi de mission (form)
     *
     * @return ViewModel
     */
    public function ajoutAction (): ViewModel
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
    public function modifierAction (): ViewModel
    {
        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        return $this->saisieAction($volumeHoraireMission->getMission()->getIntervenant(), $volumeHoraireMission);
    }



    protected function saisieAction (Intervenant $intervenant, VolumeHoraireMission $volumeHoraireMission)
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

        $hDeb = $volumeHoraireMission->getHeures();

        $form->bindRequestSave($volumeHoraireMission, $this->getRequest(), function (VolumeHoraireMission $vhm) use ($hDeb) {
            $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();
            $this->getProcessusPlafond()->beginTransaction();
            try {
                $this->getServiceMission()->saveVolumeHoraire($vhm);
                $hFin = $vhm->getHeures();
                $this->updateTableauxBord($vhm->getMission());
                if (!$this->getProcessusPlafond()->endTransaction($vhm, $typeVolumeHoraire, $hFin < $hDeb)) {
                    $this->updateTableauxBord($vhm->getMission());
                } else {
                    $this->flashMessenger()->addSuccessMessage('Suivi bien enregistré');
                }
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
                $this->em()->rollback();
            }
        });
        // on passe l'id pour pouvoir le récupérer dans la vue et mettre à jour la liste
        $form->setAttribute('data-id', $volumeHoraireMission->getId());

        $vm = new ViewModel();
        $vm->setTemplate('mission/suivi/saisie');
        $vm->setVariables(compact('form', 'title'));

        return $vm;
    }



    public function supprimerAction ()
    {
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getRealise();

        /** @var VolumeHoraireMission $volumeHoraireMission */
        $volumeHoraireMission = $this->getEvent()->getParam('volumeHoraireMission');

        $this->getProcessusPlafond()->beginTransaction();
        try {
            $this->getServiceMission()->deleteVolumeHoraire($volumeHoraireMission);
            $this->updateTableauxBord($volumeHoraireMission->getMission());
            $this->flashMessenger()->addSuccessMessage('Le suivi a bien été supprimé');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }
        $this->getProcessusPlafond()->endTransaction($volumeHoraireMission->getMission(), $typeVolumeHoraire, true);

        return new AxiosModel([]);
    }



    public function validerAction ()
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



    public function devaliderAction ()
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



    private function updateTableauxBord (Mission $mission)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            TblProvider::MISSION,
            TblProvider::PAIEMENT
        ], $mission->getIntervenant());
    }

}