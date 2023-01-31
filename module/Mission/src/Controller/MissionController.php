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
     * Retourne la liste des missions
     *
     * @return JsonModel
     */
    public function listeAction()
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $dql = "
        SELECT 
          m, tm, str, tr, valid, vh, vvh, ctr
        FROM 
          " . Mission::class . " m
          JOIN m.typeMission tm
          JOIN m.structure str
          JOIN m.missionTauxRemu tr
          JOIN " . TypeVolumeHoraire::class . " tvh WITH tvh.code = :typeVolumeHorairePrevu
          LEFT JOIN m.validations valid WITH valid.histoDestruction IS NULL
          LEFT JOIN m.volumesHoraires vh WITH vh.histoDestruction IS NULL AND vh.typeVolumeHoraire = tvh
          LEFT JOIN vh.validations vvh WITH vvh.histoDestruction IS NULL
          LEFT JOIN vh.contrat ctr WITH ctr.histoDestruction IS NULL
        WHERE 
            m.histoDestruction IS NULL 
            AND m.intervenant = :intervenant
        ";

        $queryParams = [
            'intervenant'            => $intervenant,
            'typeVolumeHorairePrevu' => TypeVolumeHoraire::CODE_PREVU,
        ];

        $query = $this->em()->createQuery($dql)->setParameters($queryParams);

        return $this->axios()->send($query);
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

        return $this->axios()->send($mission);
    }



    public function ajoutAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $mission = $this->getServiceMission()->newEntity();
        $mission->setIntervenant($intervenant);

        $canAutoValidate = $this->isAllowed($mission, Privileges::MISSION_AUTOVALIDATION);

        if ($canAutoValidate) $mission->setAutoValidation(true);

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

        if ($mission->isValide()) {
            $this->flashMessenger()->addInfoMessage('La mission est déjà validée');
        } else {
            $this->getServiceValidation()->validerMission($mission);
            $this->getServiceMission()->save($mission);
            $this->flashMessenger()->addSuccessMessage('Mission validée');
        }

        return $this->getAction();
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

        return $this->getAction();
    }
}