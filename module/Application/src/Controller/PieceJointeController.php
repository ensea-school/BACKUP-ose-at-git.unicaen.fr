<?php

namespace Application\Controller;

use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TblPieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Form\PieceJointe\Traits\ModifierTypePieceJointeStatutFormAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\PieceJointeServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\TypePieceJointeServiceAwareTrait;
use Application\Service\Traits\TypePieceJointeStatutServiceAwareTrait;
use Application\Form\PieceJointe\Traits\TypePieceJointeSaisieFormAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use UnicaenApp\View\Model\MessengerViewModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Model\JsonModel;
use Application\Service\Traits\ContextServiceAwareTrait;


/**
 * Description of UploadController
 *
 */
class PieceJointeController extends AbstractController
{
    use ContextServiceAwareTrait;
    use PieceJointeServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use TypePieceJointeSaisieFormAwareTrait;
    use ModifierTypePieceJointeStatutFormAwareTrait;
    use TypePieceJointeServiceAwareTrait;
    use TypePieceJointeStatutServiceAwareTrait;
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
        //$this->initFilters();
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $intervenant = $this->getEvent()->getParam('intervenant');
        /* @var $intervenant Intervenant */
        if (!$intervenant) {
            throw new \LogicException('Intervenant non précisé ou inexistant');
        }

        if ($this->params()->fromQuery('menu', false) !== false) { // pour gérer uniquement l'affichage du menu
            $menu = new ViewModel();
            $menu->setTemplate('application/intervenant/menu');

            return $menu;
        }


        $title = "Pièces justificatives <small>{$intervenant}</small>";

        $heuresPourSeuil = $this->getServicePieceJointe()->getHeuresPourSeuil($intervenant);
        $fournies        = $this->getServicePieceJointe()->getPiecesFournies($intervenant);
        $demandees       = $this->getServicePieceJointe()->getTypesPiecesDemandees($intervenant);
        $synthese        = $this->getServicePieceJointe()->getPiecesSynthese($intervenant);

        $annee = $this->getServiceContext()->getAnnee();

        $messages = $this->makeMessages($demandees, $fournies);

        $alertContrat = $role->getIntervenant() && $intervenant->getStatut()->getPeutAvoirContrat();

        return compact('intervenant', 'title', 'heuresPourSeuil', 'demandees', 'synthese', 'fournies', 'messages', 'alertContrat', 'annee');
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
     * @param TblPieceJointe[] $synthesePiecesJointes
     */
    protected function makeMessages($demandees, $fournies)
    {
        $role                     = $this->getServiceContext()->getSelectedIdentityRole();
        $isIntervenant            = (boolean)$role->getIntervenant();
        $nbDemandees              = 0;
        $nbFournies               = 0;
        $nbValidees               = 0;
        $nbObligatoiresNonFournis = 0;

        foreach ($demandees as $demandee) {
            if ($demandee->isObligatoire()) {
                $nbDemandees++;
                if (isset($fournies[$demandee->getTypePieceJointe()->getId()])) {
                    $pj = $fournies[$demandee->getTypePieceJointe()->getId()];
                    if (!$pj->getFichier()->isEmpty()) {
                        $nbFournies++;
                        if ($pj->getValidation()) {
                            $nbValidees++;
                        }
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
            $msgs['warning'][] = "Mais certaines doivent encore être validées " . ($isIntervenant ? 'par votre' : 'la') . " composante.";
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



    public function archiverAction()
    {
        $this->initFilters();
        /** @var PieceJointe $pj */
        $pj = $this->getEvent()->getParam('pieceJointe');

        $intervenant = $this->getServiceContext()->getSelectedIdentityRole()->getIntervenant();
        if ($intervenant && $pj->getIntervenant() != $intervenant) {
            // un intervenant tente d'archiver la PJ d'un autre intervenant
            throw new \Exception('Vous ne pouvez pas archiver la pièce justificative d\'un autre intervenant');
        }

        $pj = $this->getServicePieceJointe()->archiver($pj);
        $this->updateTableauxBord($pj->getIntervenant(), true);
        $viewModel = new ViewModel();


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
        $intervenant = $this->getEvent()->getParam('intervenant');
        $pj          = $this->getEvent()->getParam('pieceJointe');

        if (empty($pj) || $pj->estHistorise()) {
            $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');
            $pj              = $this->getServicePieceJointe()->getByType($intervenant, $typePieceJointe);
        } else {
            if ($pj->getIntervenant()->getCode() != $intervenant->getCode()) {
                // un intervenant tente d'archiver la PJ d'un autre intervenant
                throw new \Exception('Vous ne pouvez pas visualiser la liste des pièces jointes d\'un autre intervenant');
            }
        }

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
            $errors = $this->getServicePieceJointe()->ajouterFichiers($result['files'], $intervenant, $typePieceJointe);
            if (!empty($errors)) {
                return new JsonModel(['errors' => $errors]);
            }
        }

        $this->updateTableauxBord($intervenant);

        return new JsonModel();
    }



    public function telechargerAction()
    {
        /** @var Fichier $fichier */
        $fichier = $this->getEvent()->getParam('fichier');

        /** @var PieceJointe $pieceJointe */
        $pieceJointe = $fichier->getPieceJointe();

        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        if (!$pieceJointe || $pieceJointe->getIntervenant()->getCode() != $intervenant->getCode()) {
            // un intervenant tente de télécharger la PJ d'un autre intervenant
            throw new \Exception('La pièce jointe n\'existe pas ou bien elle appartient à un autre intervenant');
        }

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

        $intervenant = $this->getServiceContext()->getSelectedIdentityRole()->getIntervenant();
        if ($intervenant && $pj->getIntervenant() != $intervenant) {
            // un intervenant tente de supprimer la PJ d'un autre intervenant
            throw new \Exception('Vous ne pouvez pas supprimer la pièce jointe d\'un autre intervenant');
        }

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
            \Application\Entity\Db\Statut::class,
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
            $tpjID = $tpjs->getTypePieceJointe()->getId();
            $siId  = $tpjs->getStatutIntervenant()->getId();

            if (!isset($typesPiecesJointesStatuts[$tpjID][$siId])) {
                $typesPiecesJointesStatuts[$tpjID][$siId] = [];
            }
            $typesPiecesJointesStatuts[$tpjID][$siId][] = $tpjs;
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
            $this->flashMessenger()->addErrorMessage($this->translate($e));
        }

        return new MessengerViewModel();
    }



    public function typePieceJointeSaisieAction()
    {
        /* @var $typePieceJointe TypePieceJointe */
        $typePieceJointe = $this->getEvent()->getParam('typePieceJointe');

        $form = $this->getFormTypePieceJointeSaisie();
        if (empty($typePieceJointe)) {
            $title           = 'Création d\'un nouveau type de pièce jointe';
            $typePieceJointe = $this->getServiceTypePieceJointe()->newEntity();
            $typePieceJointe->setOrdre(9999);
        } else {
            $title = 'Édition du type de pièce jointe';
        }
        $form->bindRequestSave($typePieceJointe, $this->getRequest(), $this->getServiceTypePieceJointe());

        return compact('form', 'title');
    }



    public function modifierTypePieceJointeStatutAction()
    {
        /* @var $tpjs TypePieceJointeStatut */
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
        } else {
            $title             = 'Édition du paramètre de gestion de pièce justificative';
            $typePieceJointe   = $tpjs->getTypePieceJointe();
            $statutIntervenant = $tpjs->getStatutIntervenant();
        }
        $form->buildAnnees($tpjs);
        $form->bindRequestSave($tpjs, $this->getRequest(), $this->getServiceTypePieceJointeStatut());

        return compact('form', 'title', 'typePieceJointe', 'statutIntervenant');
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
                    $txt .= ':' . $this->translate($e);
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
            $this->flashMessenger()->addErrorMessage($this->translate($e));
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

        //Récupérer tous les intervenants avec le même code intervenant
        $intervenants = $this->getServiceIntervenant()->getIntervenants($intervenant);

        //On recalcule le tbl piece_jointe pour tous les intervenants ayant le même code intervenant que l'intervenant de l'année en cours
        foreach ($intervenants as $objectIntervenant) {
            $this->getServiceWorkflow()->calculerTableauxBord([
                'piece_jointe',
            ], $objectIntervenant);
        }
    }

}
