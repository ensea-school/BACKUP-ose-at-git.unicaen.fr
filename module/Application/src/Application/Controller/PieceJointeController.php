<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Exception\DbException;
use Application\Form\PieceJointe\Traits\ModifierTypePieceJointeStatutFormAwareTrait;
use Application\Service\Traits\PieceJointeAwareTrait;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use Application\Service\Traits\TypePieceJointeAwareTrait;
use Application\Service\Traits\TypePieceJointeStatutAwareTrait;
use Application\Form\PieceJointe\Traits\TypePieceJointeSaisieFormAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Service\Traits\ContextAwareTrait;


/**
 * Description of UploadController
 *
 */
class PieceJointeController extends AbstractController
{
    use ContextAwareTrait;
    use PieceJointeAwareTrait;
    use StatutIntervenantAwareTrait;
    use TypePieceJointeSaisieFormAwareTrait;
    use ModifierTypePieceJointeStatutFormAwareTrait;
    use TypePieceJointeAwareTrait;
    use TypePieceJointeStatutAwareTrait;
    use WorkflowServiceAwareTrait;



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs
     * éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\PieceJointe::class,
            \Application\Entity\Db\Fichier::class,
            \Application\Entity\Db\Validation::class,
        ]);
    }



    /**
     *
     * @return ViewModel
     * @throws \LogicException
     */
    public function indexAction()
    {
        $this->initFilters();

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        $title = "Pièces justificatives <small>{$intervenant}</small>";

        $demandees       = $this->getServicePieceJointe()->getTypesPiecesDemandees($intervenant);
        $heuresPourSeuil = $this->getServicePieceJointe()->getHeuresPourSeuil($intervenant);
        $fournies        = $this->getServicePieceJointe()->getPiecesFournies($intervenant);

        $messages = $this->makeMessages($demandees, $fournies);

        $alertContrat = $role->getIntervenant() && $intervenant->getStatut()->getPeutAvoirContrat();

        return compact('intervenant', 'title', 'demandees', 'heuresPourSeuil', 'fournies', 'messages', 'alertContrat');
    }



    /**
     *
     * @return ViewModel
     */
    public function infosAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */

        $demandees = $this->getServicePieceJointe()->getTypesPiecesDemandees($intervenant);
        $fournies  = $this->getServicePieceJointe()->getPiecesFournies($intervenant);

        $messages = $this->makeMessages($demandees, $fournies);

        return compact('messages');
    }



    /**
     * @param TypePieceJointe[] $demandees
     * @param PieceJointe[]     $fournies
     */
    protected function makeMessages($demandees, $fournies)
    {
        $role          = $this->getServiceContext()->getSelectedIdentityRole();
        $isIntervenant = (boolean)$role->getIntervenant();
        $nbDemandees   = 0;
        $nbFournies    = 0;
        $nbValidees    = 0;

        foreach ($demandees as $demandee) {
            $nbDemandees++;
            if (isset($fournies[$demandee->getId()])) {
                $pj = $fournies[$demandee->getId()];
                if (!$pj->getFichier()->isEmpty()) {
                    $nbFournies++;
                    if ($pj->getValidation()) {
                        $nbValidees++;
                    }
                }
            }
        }

        $msgs = [];

        if (0 == $nbDemandees) {
            $msgs['info'][] = 'Aucune pièce justificative n\'est à fournir';
        } elseif ($nbFournies < $nbDemandees) {
            $msgs['danger'][] = "Des pièces justificatives obligatoires n'ont pas été fournies.";
        } elseif ($nbFournies == $nbDemandees && $nbValidees == $nbDemandees) {
            $msgs['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies et validées.";
        } elseif ($nbFournies == $nbDemandees && $nbValidees < $nbFournies) {
            $msgs['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies.";
            $msgs['danger'][]  = "Mais certaines doivent encore être validées par " . ($isIntervenant ? 'votre' : 'la') . " composante.";
        }

        return $msgs;
    }



    public function validationAction()
    {
        $this->initFilters();

        $intervenant = $this->getEvent()->getParam('intervenant');
        $tpj         = $this->getEvent()->getParam('typePieceJointe');
        $pj          = $this->getServicePieceJointe()->getByType($intervenant, $tpj);

        return compact('pj');
    }



    public function validerAction()
    {
        $this->initFilters();

        /** @var PieceJointe $pj */
        $pj = $this->getEvent()->getParam('pieceJointe');
        $this->getServicePieceJointe()->valider($pj);
        $this->updateTableauxBord($pj->getIntervenant(), true);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/piece-jointe/validation');
        $viewModel->setVariable('pj', $pj);

        return $viewModel;
    }



    public function devaliderAction()
    {
        $this->initFilters();

        /** @var PieceJointe $pj */
        $pj = $this->getEvent()->getParam('pieceJointe');
        $this->getServicePieceJointe()->devalider($pj);
        $this->updateTableauxBord($pj->getIntervenant(), true);

        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/piece-jointe/validation');
        $viewModel->setVariable('pj', $pj);

        return $viewModel;
    }



    public function listerAction()
    {
        $this->initFilters();

        $intervenant     = $this->getEvent()->getParam('intervenant');
        $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');
        $pj              = $this->getServicePieceJointe()->getByType($intervenant, $typePieceJointe);

        return compact('pj');
    }



    public function televerserAction()
    {
        $intervenant     = $this->getEvent()->getParam('intervenant');
        $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $pj = $this->getServicePieceJointe()->ajouterFichiers($result['files'], $intervenant, $typePieceJointe);
        }

        $this->updateTableauxBord($intervenant);

        return new JsonModel();
    }



    public function telechargerAction()
    {
        $fichier = $this->getEvent()->getParam('fichier');

        $this->uploader()->download($fichier);
    }



    public function supprimerAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        /** @var PieceJointe $pj */
        $pj      = $this->getEvent()->getParam('pieceJointe');
        $fichier = $this->getEvent()->getParam('fichier');

        if ($fichier) {
            $this->getServicePieceJointe()->supprimerFichier($fichier, $pj);
        }

        $this->updateTableauxBord($pj->getIntervenant());

        return new JsonModel();
    }



    /* Actions liées à la configuration des PJ */

    public function configurationAction()
    {
        return [];
    }



    public function typePieceJointeStatutAction()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\TypePieceJointe::class,
            \Application\Entity\Db\StatutIntervenant::class,
            \Application\Entity\Db\TypePieceJointeStatut::class,
        ]);

        $anneeId = $this->getServiceContext()->getAnnee()->getId();

        $typesPiecesJointes  = $this->getServiceTypePieceJointe()->getList();
        $statuts             = $this->getServiceStatutIntervenant()->getList();
        $statutsIntervenants = [];
        foreach ($statuts as $statut) {
            $statutsIntervenants[$statut->getTypeIntervenant()->getId()][] = $statut;
        }

        $dql = "
        SELECT
          tpjs, adeb, afin
        FROM
          " . \Application\Entity\Db\TypePieceJointeStatut::class . " tpjs
          LEFT JOIN tpjs.anneeDebut adeb
          LEFT JOIN tpjs.anneeFin afin
        WHERE
          COALESCE($anneeId,$anneeId) BETWEEN COALESCE(adeb.id,$anneeId) AND COALESCE(afin.id,$anneeId)
        "; // COALESCE($anneeId,$anneeId) bizarre mais c'est pour contourner un bug de doctrine!!!!!!

        /* @var $tpjss TypePieceJointeStatut[] */
        $tpjss                     = $this->em()->createQuery($dql)->getResult();
        $typesPiecesJointesStatuts = [];
        foreach ($tpjss as $tpjs) {
            $typesPiecesJointesStatuts[$tpjs->getTypePieceJointe()->getId()][$tpjs->getStatutIntervenant()->getId()] = $tpjs;
        }

        return compact('typesPiecesJointes', 'statutsIntervenants', 'typesPiecesJointesStatuts');
    }



    public function typePieceJointeDeleteAction()
    {
        $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');

        try {
            $this->getServiceTypePieceJointe()->delete($typePieceJointe);
            $this->flashMessenger()->addSuccessMessage("Type de pièce jointe supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }



    public function typePieceJointeSaisieAction()
    {
        /* @var $typePieceJointe TypePieceJointe */
        $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');

        $form = $this->getFormTypePieceJointeSaisie();
        if (empty($typePieceJointe)) {
            $title = 'Création d\'un nouveau type de pièce jointe';
            try {
                $typePieceJointe = $this->getServiceTypePieceJointe()->newEntity();
                $typePieceJointe->setOrdre(9999);
            } catch (\Exception $e) {
                $e = DbException::translate($e);
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
            if ($typePieceJointe) {
                $form->setObject($typePieceJointe);
            }
        } else {
            $title = 'Édition du type de pièce jointe';
            $form->bind($typePieceJointe);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceTypePieceJointe()->save($typePieceJointe);
                    $form->get('id')->setValue($typePieceJointe->getId()); // transmet le nouvel ID
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            }
        }

        return compact('form', 'title');
    }



    public function modifierTypePieceJointeStatutAction()
    {
        /* @var $tpjs TypePieceJointeStatut */
        /* @var $form ModifierTypePieceJointeStatutForm */
        $tpjs = $this->getEvent()->getParam('typePieceJointeStatut'); // $tpjs1 pour existence sur supprimer

        $form = $this->getFormModifierTypePieceJointeStatut();
        if (empty($tpjs)) {
            $title             = 'Nouveau paramètre de gestion de pièce justificative';
            $tpjs              = $this->getServiceTypePieceJointeStatut()->newEntity();
            $typePieceJointe   = $this->getEvent()->getParam('typePieceJointe');
            $statutIntervenant = $this->getEvent()->getParam('statutIntervenant');
            $tpjs->setTypePieceJointe($typePieceJointe);
            $tpjs->setStatutIntervenant($statutIntervenant);
            $tpjs->setObligatoire(true);
            $form->bind($tpjs);
        } else {
            $title = 'Édition du paramètre de gestion de pièce justificative';
            $form->bind($tpjs);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try {
                    $this->getServiceTypePieceJointeStatut()->save($tpjs);
                    $this->flashMessenger()->addSuccessMessage('Enregistrement effectué');
                    $form->get('id')->setValue($tpjs->getId()); // transmet le nouvel ID
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $this->flashMessenger()->addErrorMessage($e->getMessage());
                }
            }
        }

        return compact('form', 'title');
    }



    public function typePieceJointeTrierAction()
    {
        /* @var $tpj TypePieceJointe */
        $txt       = 'result=';
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre     = 1;
        foreach ($champsIds as $champId) {
            $txt .= $champId . '=>';
            $tpj = $this->getServiceTypePieceJointe()->get($champId);
            if ($tpj) {
                $txt .= ';' . $tpj->getCode();
                $tpj->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceTypePieceJointe()->save($tpj);
                } catch (\Exception $e) {
                    $e   = DbException::translate($e);
                    $txt .= ':' . $e->getMessage();
                }
            }
        }

        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }



    public function deleteTypePieceJointeStatutAction()
    {
        $typePieceJointeStatut = $this->getEvent()->getParam('typePieceJointeStatut');

        try {
            $this->getServiceTypePieceJointeStatut()->delete($typePieceJointeStatut);
            $this->flashMessenger()->addSuccessMessage("Type de pièce jointe supprimé avec succès.");
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(DbException::translate($e)->getMessage());
        }

        return new MessengerViewModel();
    }



    private function updateTableauxBord(Intervenant $intervenant, $validation = false)
    {
        $this->getServiceWorkflow()->calculerTableauxBord([
            'piece_jointe_fournie',
            'agrement',
            'contrat',
        ], $intervenant);
    }

}
