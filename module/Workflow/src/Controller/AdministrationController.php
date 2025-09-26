<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Form\DependanceFormAwareTrait;
use Workflow\Form\EtapeFormAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of AdministrationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use WorkflowServiceAwareTrait;
    use EtapeFormAwareTrait;
    use DependanceFormAwareTrait;

    public function indexAction(): VueModel
    {
        $props = [
            'canEdit' => $this->isAllowed(Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_EDITION)),
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('workflow/administration');
        $vueModel->setVariables($props);

        return $vueModel;
    }



    public function dataAction(): AxiosModel
    {
        // Le cache est vidé systématiquement ici pour éviter tout problème
        //$this->getServiceWorkflow()->clearEtapesCache();

        $etapes = array_values($this->getServiceWorkflow()->getEtapes());

        $properties = [
            'id',
            'code',
            ['perimetre', [
                'code',
                'libelle',
            ]],
            'libelleIntervenant',
            'libelleAutres',
            ['dependances', [
                'id',
                ['etapePrecedante', [
                    'code',
                    'libelleAutres',
                ]],
                'active',
                ['typeIntervenant', [
                    'code',
                    'libelle',
                ]],
                ['perimetre', [
                    'code',
                    'libelle',
                ]],
                'avancementLibelle',
            ]],
        ];

        return new AxiosModel($etapes, $properties);
    }



    public function modificationEtapeAction(): array
    {
        /* @var $workflowEtape WorkflowEtape */
        $workflowEtape = $this->getEvent()->getParam('workflowEtape');

        $title = "Personnalisation des libellés de l'étape";

        $form = $this->getFormEtape();
        $form->bindRequestSave($workflowEtape, $this->getRequest(), function ($workflowEtape) {
            try {
                $this->getServiceWorkflow()->saveEtape($workflowEtape);
            } catch (\Throwable $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('title', 'form');
    }



    public function saisieDependanceAction(): array
    {
        /* @var $workflowEtapeDependance WorkflowEtapeDependance */
        $workflowEtapeDependance = $this->getEvent()->getParam('workflowEtapeDependance');

        /* @var $workflowEtape WorkflowEtape */
        $workflowEtape = $this->getEvent()->getParam('workflowEtape');

        if (!$workflowEtapeDependance) {
            $workflowEtapeDependance = new WorkflowEtapeDependance();
            $workflowEtapeDependance->setEtapeSuivante($workflowEtape);
            $workflowEtape->addDependance($workflowEtapeDependance);
        }
        $title = $workflowEtape->getLibelleAutres();

        $form = $this->getFormWorkflowDependance();
        $form->init2($workflowEtapeDependance);
        $form->bindRequestSave($workflowEtapeDependance, $this->getRequest(), function ($workflowEtapeDependance) {
            try {
                $this->getServiceWorkflow()->saveEtapeDependance($workflowEtapeDependance);
            } catch (\Throwable $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('title', 'form');
    }



    public function suppressionDependanceAction(): AxiosModel
    {
        /* @var $workflowEtapeDependance WorkflowEtapeDependance */
        $workflowEtapeDependance = $this->getEvent()->getParam('workflowEtapeDependance');

        if (!$workflowEtapeDependance) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        try {
            $this->getServiceWorkflow()->deleteEtapeDependance($workflowEtapeDependance);

            $this->flashMessenger()->addSuccessMessage("Dépendance supprimée avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new AxiosModel([]);
    }



    public function triAction(): AxiosModel
    {
        /** @var array $etapes */
        $etapes = $this->axios()->fromPost('etapes');

        try {
            $this->getServiceWorkflow()->trier($etapes);
            $this->flashMessenger()->addSuccessMessage('Nouvel ordonnancement du workflow bien pris en compte');
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return $this->dataAction();
    }

}