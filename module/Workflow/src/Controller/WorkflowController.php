<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\WorkflowServiceAwareTrait;


class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use TableauBordServiceAwareTrait;


    public function calculerToutAction(): array
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



    public function feuilleDeRouteDataAction(): AxiosModel
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
            'navigable',
            'realisationPourc',
            'objectif',
            'realisation',
            ['structures', [
                'libelle',
                'atteignable',
                'whyNonAtteignable',
                'courante',
                'navigable',
                'realisationPourc',
                'objectif',
                'realisation',
            ]],
        ];

        return new AxiosModel(array_values($feuilleDeRoute->getEtapes()), $properties);
    }



    public function feuilleDeRouteRefreshAction(): AxiosModel
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



    public function feuilleDeRouteNavAction(): AxiosModel
    {
        /* @var $intervenant Intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $etape = $this->axios()->fromPost('etape');

        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);
        $nextEtape      = $feuilleDeRoute->getNext($etape);

        $props = [
            'url',
            'libelle',
        ];

        return new AxiosModel($nextEtape, $props);
    }
}