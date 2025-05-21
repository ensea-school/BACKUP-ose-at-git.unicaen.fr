<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use UnicaenApp\Exception\LogicException;
use UnicaenApp\View\Model\MessengerViewModel;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;
use Workflow\Entity\Db\WfEtapeDep;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Form\DependanceFormAwareTrait;
use Workflow\Service\WfEtapeDepServiceAwareTrait;
use Workflow\Service\WfEtapeServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use DependanceFormAwareTrait;
    use TableauBordServiceAwareTrait;

    use WfEtapeDepServiceAwareTrait;
    use WfEtapeServiceAwareTrait;


    public function administrationAction(): VueModel
    {
        $props = [
            'canEdit' => $this->isAllowed(Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_EDITION)),
        ];

        $vueModel = new VueModel();
        $vueModel->setTemplate('workflow/administration');
        $vueModel->setVariables($props);

        return $vueModel;
    }



    public function administrationDataAction(): AxiosModel
    {
        // Le cache est vidé systématiquement ici pour éviter tout problème
        $this->getServiceWorkflow()->clearEtapesCache();

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
                'avancement',
            ]],
        ];

        return new AxiosModel($etapes, $properties);
    }



    public function administrationSaisieDependanceAction(): array
    {
        /* @var $workflowEtapeDependance WorkflowEtapeDependance */
        $workflowEtapeDependance = $this->getEvent()->getParam('workflowEtapeDependance');

        /* @var $workflowEtape WorkflowEtape */
        $workflowEtape = $this->getEvent()->getParam('workflowEtape');

        if (!$workflowEtapeDependance) {
            $workflowEtapeDependance = new WorkflowEtapeDependance();
            $workflowEtapeDependance->setEtapeSuivante($workflowEtape);
            $workflowEtape->addDependance($workflowEtapeDependance);

            $title = 'Ajout d\'une dépendance';
        } else {
            $title = 'Modification d\'une dépendance';
        }

        $form = $this->getFormWorkflowDependance();
        $form->init2($workflowEtape);
        $form->bindRequestSave($workflowEtapeDependance, $this->getRequest(), function ($workflowEtapeDependance) {
            try {
                $this->getServiceWorkflow()->saveEtapeDependance($workflowEtapeDependance);
            } catch (\Throwable $e) {
                $this->flashMessenger()->addErrorMessage($this->translate($e));
            }
        });

        return compact('title', 'form');
    }



    public function dependancesAction()
    {
        $dql    = '
        SELECT
          we
        FROM
          Workflow\Entity\Db\WfEtape we
        ORDER BY
          we.ordre
        ';
        $etapes = $this->em()->createQuery($dql)->getResult();


        $dql = '
        SELECT
          wed, es, ep
        FROM
          Workflow\Entity\Db\WfEtapeDep wed
          JOIN wed.etapeSuiv es
          JOIN wed.etapePrec ep
        ORDER BY
          es.ordre, ep.ordre
        ';

        $query = $this->em()->createQuery($dql);

        $d = $query->getResult();
        /* @var $d WfEtapeDep[] */
        $deps = [];
        foreach ($d as $dep) {
            $deps[$dep->getEtapeSuiv()->getId()][$dep->getEtapePrec()->getId()] = $dep;
        }

        return compact('etapes', 'deps');
    }



    public function suppressionDepAction()
    {
        if (!($wfEtapeDep = $this->getEvent()->getParam('wfEtapeDep'))) {
            throw new \RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }

        $form = $this->makeFormSupprimer(function () use ($wfEtapeDep) {
            $this->getServiceWfEtapeDep()->delete($wfEtapeDep);
        });

        return compact('wfEtapeDep', 'form');
    }



    public function calculerToutAction()
    {
        $action = $this->params()->fromQuery('action') === '1';
        $title  = 'Calcul du workflow...';
        $error  = null;

        if ($action) {
            try {
                $this->getServiceWorkflow()->calculerTout();
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return compact('action', 'title', 'error');
    }



    public function feuilleDeRouteRefreshAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if ($intervenant) {
            $errors = $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        if (empty($errors)) {
            $this->flashMessenger()->addSuccessMessage('Feuille de route actualisée.');
        } else {
            foreach ($errors as $error) {
                $this->flashMessenger()->addErrorMessage($error->getMessage());
            }
        }

        return new MessengerViewModel();
    }



    public function feuilleDeRouteBtnNextAction()
    {
        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $wfEtapeCode = $this->params()->fromRoute('wfEtapeCode');
        if (!$wfEtapeCode) {
            throw new LogicException('L\'étape du workflow doit être précisée');
        }

        return compact('intervenant', 'wfEtapeCode');
    }
}