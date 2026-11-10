<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Service\DiagnosticFeuilleDeRouteServiceAwareTrait;
use Workflow\Service\EtatLieuxIntervenantServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


class WorkflowController extends AbstractController
{
    use ContextServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use TableauBordServiceAwareTrait;
    use DiagnosticFeuilleDeRouteServiceAwareTrait;
    use EtatLieuxIntervenantServiceAwareTrait;


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
        $etapes         = array_values(array_filter(
                                           $feuilleDeRoute->getEtapes(),
                                           static fn($etape) => $etape->isVisible(),
                                       ));

        foreach ($etapes as $numero => $etape) {
            $etape->numero = $numero + 1;
        }

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
            ['structures',
             [
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

        return new AxiosModel($etapes, $properties);
    }



    public function feuilleDeRouteRefreshAction(): AxiosModel
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if ($intervenant) {
            $errors = $this->getServiceWorkflow()->calculerTableauxBord([], $intervenant);
        }

        // En cas d'erreur, les messages sont émis directement par WorkflowService::calculerTableauxBord()
        if (empty($errors)) {
            $this->flashMessenger()->addSuccessMessage('Feuille de route actualisée.');
        }

        return $this->feuilleDeRouteDataAction();
    }



    public function feuilleDeRouteDiagnosticAction(): AxiosModel
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        try {
            $diagnostic = $this->getServiceDiagnosticFeuilleDeRoute()->diagnostiquer($intervenant);
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage('Le diagnostic n\'a pas pu être établi : ' . htmlspecialchars($this->translate($e)));
            $diagnostic = null;
        }

        return new AxiosModel($diagnostic);
    }



    public function feuilleDeRouteEtatLieuxAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');
        $service     = $this->getServiceEtatLieuxIntervenant();

        try {
            $pdf = $service->genererPdf($intervenant);
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage('L\'état des lieux n\'a pas pu être généré : ' . htmlspecialchars($this->translate($e)));

            return $this->redirect()->toRoute('intervenant/voir', ['intervenant' => $intervenant->getId()]);
        }

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $service->getNomFichier($intervenant) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        echo $pdf;
        die();
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