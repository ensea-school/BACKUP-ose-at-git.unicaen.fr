<?php

namespace Workflow\Controller;

use Application\Controller\AbstractController;
use Application\Provider\Privileges;
use Administration\Service\ParametresServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use UnicaenVue\View\Model\AxiosModel;
use UnicaenVue\View\Model\VueModel;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Entity\Db\WorkflowEtapeDependance;
use Workflow\Form\DependanceFormAwareTrait;
use Workflow\Form\EtapeFormAwareTrait;
use Workflow\Service\ImportInvalideException;
use Workflow\Service\RapportParametrageServiceAwareTrait;
use Workflow\Service\TransfertParametrageServiceAwareTrait;
use Workflow\Service\WorkflowServiceAwareTrait;


/**
 * Description of AdministrationController
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationController extends AbstractController
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use RapportParametrageServiceAwareTrait;
    use TransfertParametrageServiceAwareTrait;
    use EtapeFormAwareTrait;
    use DependanceFormAwareTrait;

    public function indexAction(): VueModel
    {
        $props = [
            'canEdit' => $this->isAllowed(Privileges::getResourceId(Privileges::WORKFLOW_DEPENDANCES_EDITION)),
            'annee'       => $this->getServiceContext()->getAnnee()->getLibelle(),
            'anneePassee' => $this->getServiceContext()->getAnnee()->getId() < (int)$this->getServiceParametres()->get('annee'),
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
                'avancement',
                'avancementLibelle',
            ]],
        ];

        return new AxiosModel($etapes, $properties);
    }



    public function rapportAction()
    {
        $service = $this->getServiceRapportParametrage();

        try {
            $pdf = $service->genererPdf();
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage('Le rapport n\'a pas pu être généré : ' . $this->translate($e));

            return $this->redirect()->toRoute('workflow/administration');
        }

        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $service->getNomFichier() . '"');
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        echo $pdf;
        die();
    }



    public function exportAction()
    {
        try {
            $json = $this->getServiceTransfertParametrage()->exporterJson();
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage('Le paramétrage n\'a pas pu être exporté : ' . $this->translate($e));

            return $this->redirect()->toRoute('workflow/administration');
        }

        $nomFichier = $this->getServiceRapportParametrage()->getNomFichier('workflow-configuration', 'json');

        header('Content-type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $nomFichier . '"');
        header('Pragma: no-cache');
        header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        echo $json;
        die();
    }



    public function importAction(): AxiosModel
    {
        $fichier = $this->getRequest()->getFiles()->get('fichier');

        try {
            if (!$fichier || UPLOAD_ERR_OK !== ($fichier['error'] ?? UPLOAD_ERR_NO_FILE)) {
                throw new \RuntimeException('Aucun fichier n\'a été reçu.');
            }

            $anneesSuivantes = 'annee' !== $this->getRequest()->getPost('portee');
            $resultat        = $this->getServiceTransfertParametrage()->importerJson(file_get_contents($fichier['tmp_name']), $anneesSuivantes);

            $annee = $this->getServiceContext()->getAnnee();
            $this->flashMessenger()->addSuccessMessage(sprintf(
                'Paramétrage du workflow importé pour l\'année %s %s : '
                . '%d étape(s) mise(s) à jour, %d dépendance(s) ajoutée(s), %d modifiée(s), %d supprimée(s)%s. '
                . ($anneesSuivantes ? 'Les années passées n\'ont pas été modifiées. ' : 'Les autres années n\'ont pas été modifiées. ')
                . 'Pensez à recalculer le tableau de bord workflow (bin/ose calcul-tableaux-bord) pour mettre à jour les feuilles de route.',
                $annee->getLibelle(),
                $anneesSuivantes ? sprintf('et les suivantes (jusqu\'à %d/%d)', $resultat['anneeFin'], $resultat['anneeFin'] + 1) : 'uniquement',
                $resultat['etapes'], $resultat['ajoutees'], $resultat['modifiees'], $resultat['supprimees'],
                $resultat['anneesAlignees'] ? sprintf(' ; %d année(s) suivante(s) qui avaient été personnalisées ont été réalignées', $resultat['anneesAlignees']) : ''
            ));
            foreach ($resultat['avertissements'] as $avertissement) {
                $this->flashMessenger()->addWarningMessage(htmlspecialchars($avertissement));
            }
        } catch (ImportInvalideException $e) {
            $erreurs = $e->getErreurs();
            $message = '<strong>Fichier non conforme, aucune modification n\'a été faite.</strong><ul class="mb-0">';
            foreach (array_slice($erreurs, 0, 10) as $erreur) {
                $message .= '<li>' . htmlspecialchars($erreur) . '</li>';
            }
            if (count($erreurs) > 10) {
                $message .= '<li>... et ' . (count($erreurs) - 10) . ' autre(s) erreur(s)</li>';
            }
            $this->flashMessenger()->addErrorMessage($message . '</ul>');
        } catch (\Throwable $e) {
            $this->flashMessenger()->addErrorMessage('Import impossible, aucune modification n\'a été faite : '
                . htmlspecialchars($this->translate($e)));
        }

        return $this->dataAction();
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