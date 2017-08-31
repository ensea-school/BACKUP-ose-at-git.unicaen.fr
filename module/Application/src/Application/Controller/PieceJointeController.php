<?php

namespace Application\Controller;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Exception\DbException;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\PieceJointeAwareTrait;
use Application\Service\Traits\TypePieceJointeAwareTrait;
use Application\Form\PieceJointe\Traits\TypePieceJointeSaisieFormAwareTrait;
use Doctrine\ORM\Query\Expr\Select;
use UnicaenApp\View\Model\MessengerViewModel;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Service\Traits\ContextAwareTrait;
use Zend\Db\Sql\Expression;

/**
 * Description of UploadController
 *
 */
class PieceJointeController extends AbstractController
{
    use ContextAwareTrait;
    use PieceJointeAwareTrait;
    use TypePieceJointeSaisieFormAwareTrait;
    use TypePieceJointeAwareTrait;

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
     * @param PieceJointe[] $fournies
     */
    protected function makeMessages($demandees, $fournies)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $isIntervenant = (boolean)$role->getIntervenant();
        $nbDemandees = 0;
        $nbFournies = 0;
        $nbValidees = 0;

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
            $msgs['warning'][] = "Des pièces justificatives obligatoires n'ont pas été fournies.";
        } elseif ($nbFournies == $nbDemandees && $nbValidees == $nbDemandees) {
            $msgs['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies et validées.";
        } elseif ($nbFournies == $nbDemandees && $nbValidees < $nbFournies) {
            $msgs['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies.";
            $msgs['warning'][] = "Mais certaines doivent encore être validées par " . ($isIntervenant ? 'votre' : 'la') . " composante.";
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

        $pj = $this->getEvent()->getParam('pieceJointe');
//        try {
        $this->getServicePieceJointe()->valider($pj);
        //           $this->flashMessenger()->addSuccessMessage('Validation effectuée');
        //       }catch(\Exception $e){
        //           $this->flashMessenger()->addErrorMessage( DbException::translate($e)->getMessage() );
        //       }

        $viewModel = new ViewModel();
        $viewModel->setTemplate('application/piece-jointe/validation');
        $viewModel->setVariable('pj', $pj);
        return $viewModel;
    }



    public function devaliderAction()
    {
        $this->initFilters();

        $pj = $this->getEvent()->getParam('pieceJointe');
        $this->getServicePieceJointe()->devalider($pj);

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

        $pj      = $this->getEvent()->getParam('pieceJointe');
        $fichier = $this->getEvent()->getParam('fichier');

        if ($fichier) {
            $this->getServicePieceJointe()->supprimerFichier($fichier, $pj);
        }

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
            \Application\Entity\Db\TypePieceJointeStatut::class,
        ]);

        $qb = $this->em()->getRepository(\Application\Entity\Db\TypePieceJointe::class)->createQueryBuilder("tpj")
            ->select("tpj")
            ->orderBy("tpj.ordre");
        $typesPiecesJointes = $qb->getQuery()->getResult();

        $qb = $this->em()->getRepository(\Application\Entity\Db\StatutIntervenant::class)->createQueryBuilder("si")
            ->select("si")
            ->andWhere("si.peutChoisirDansDossier = 1")
            ->orderBy("si.ordre");
        $statutsIntervenants = $qb->getQuery()->getResult();

        $qb = $this->em()->getRepository(\Application\Entity\Db\TypePieceJointeStatut::class)->createQueryBuilder("tpjs")
            ->select("tpjs, tpj, si")
            ->join("tpjs.type", "tpj")
            ->join("tpjs.statut", "si")
            ->orderBy("si.libelle, tpj.libelle");
//        $typesPiecesJointesStatuts = $qb->getQuery()->getResult();

        $typesPiecesJointesStatuts = [];
        foreach ($qb->getQuery()->getResult() as $tpjs) {
            /* @var $tpjs TypePieceJointeStatut */
            $typesPiecesJointesStatuts[$tpjs->getType()->getId()][$tpjs->getPremierRecrutement()][$tpjs->getStatut()->getId()] = $tpjs;
        }

        return [
            'typesPiecesJointes'        => $typesPiecesJointes,
            'statutsIntervenants'       => $statutsIntervenants,
            'typesPiecesJointesStatuts' => $typesPiecesJointesStatuts,
        ];
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
                $txt = '';
                foreach ($e as $key => $value) {
                    $txt .= '[' . $key . ']=' . $value . '---------------------------------------------- ';
                }
                $e = DbException::translate($e);
                $this->flashMessenger()->addInfoMessage($txt);
                $this->flashMessenger()->addErrorMessage($e->getMessage());
            }
            if ($typePieceJointe)
                $form->setObject($typePieceJointe);
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
        $type = $this->context()->mandatory()->typePieceJointeFromRoute();
        $statut = $this->context()->mandatory()->statutIntervenantFromRoute();
        $premierRecrutement = $this->params()->fromRoute("premierRecrutement");

        if (null === $premierRecrutement) {
            throw new \LogicException("Paramètre manquant : premierRecrutement");
        }

        $obligatoireValueOptions = [
            1 => "Obligatoire",
            0 => "Facultatif",
            2 => "Non attendu",
        ];

        $qb = $this->em()->getRepository(\Application\Entity\Db\TypePieceJointeStatut::class)->createQueryBuilder("tpjs")
            ->select("tpjs, tpj, si")
            ->join("tpjs.type", "tpj", \Doctrine\ORM\Query\Expr\Join::WITH, "tpj = :tpj")
            ->join("tpjs.statut", "si", \Doctrine\ORM\Query\Expr\Join::WITH, "si = :si")
            ->andWhere("tpjs.premierRecrutement = :pr")
            ->orderBy("si.libelle, tpj.libelle")
            ->setParameter('tpj', $type)
            ->setParameter('si', $statut)
            ->setParameter('pr', $premierRecrutement);
        $tpjs = $qb->getQuery()->getOneOrNullResult();

        if (!$tpjs) {
            $tpjs = new TypePieceJointeStatut();
            $tpjs
                ->setType($type)
                ->setStatut($statut)
                ->setPremierRecrutement((boolean)$premierRecrutement)
                ->setObligatoire('2');
        }

        if ($this->getRequest()->isPost()) {

            $obligatoire = (int)$this->params()->fromPost('obligatoire', 2);
            $seuilHetd = (int)$this->params()->fromPost('seuil_heures');

            if (!array_key_exists($obligatoire, $obligatoireValueOptions)) {
                exit;
            }

            // non attendu <=> suppression
            if (2 === $obligatoire) {
                $qb = $this->em()->remove($tpjs);
            } // obligatoire ou facultatif
            else {
                $tpjs
                    ->setObligatoire((boolean)$obligatoire)
                    ->setSeuilHetd($seuilHetd ?: null);

                $this->em()->persist($tpjs);
            }

            $this->em()->flush();

            exit;
        }

        return [
            'tpjs' => $tpjs,
            'obligatoireValueOptions' => $obligatoireValueOptions,
        ];
    }


    public function typePieceJointeTrierAction()
    {
        /* @var $tpj TypePieceJointe */
        $txt='result=';
        $champsIds = explode(',', $this->params()->fromPost('champsIds', ''));
        $ordre = 1;
        foreach ($champsIds as $champId) {
            $txt.=$champId.'=>';
            $tpj = $this->getServiceTypePieceJointe()->get($champId);
            if ($tpj) {
                $txt .= ';' . $tpj->getCode();
                $tpj->setOrdre($ordre);
                $ordre++;
                try {
                    $this->getServiceTypePieceJointe()->save($tpj);
                } catch (\Exception $e) {
                    $e = DbException::translate($e);
                    $txt .= ':' . $e->getMessage();
                }
            }
        }
        return new JsonModel(['msg' => 'Tri des champs effectué']);
    }
}