<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use UnicaenApp\Exception\LogicException;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\WfEtapeDepServiceAwareTrait;
use Workflow\Service\WfEtapeServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    use WfEtapeDepServiceAwareTrait;
    use WfEtapeServiceAwareTrait;


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



    public function feuilleDeRouteDataAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);

        $properties = [
            'code',
            'numero',
            'libelle',
            'url',
            'atteignable',
            'whyNonAtteignable',
            'courante',
            'allowed',
            'realisationPourc',
            'objectif',
            'realisation',
            ['structures', [
                'libelle',
                'atteignable',
                'whyNonAtteignable',
                'courante',
                'allowed',
                'realisationPourc',
                'objectif',
                'realisation',
            ]],
        ];

        return new AxiosModel(array_values($feuilleDeRoute->getEtapes()), $properties);
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

        return $this->feuilleDeRouteDataAction();
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