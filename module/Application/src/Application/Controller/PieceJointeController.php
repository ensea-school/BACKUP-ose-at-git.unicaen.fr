<?php

namespace Application\Controller;

use Application\Assertion\FichierAssertion;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\TypePieceJointeStatut;
use Application\Service\Process\PieceJointeProcess;
use Application\Service\Traits\PieceJointeAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Service\Traits\ContextAwareTrait;

/**
 * Description of UploadController
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeController extends AbstractController implements WorkflowIntervenantAwareInterface
{
    use ContextAwareTrait;
    use WorkflowIntervenantAwareTrait;
    use PieceJointeAwareTrait;

    /**
     * @var ViewModel
     */
    private $view;

    /**
     * @var Fichier
     */
    private $fichier;

    /**
     * @var PieceJointeProcess
     */
    private $process;



    /**
     *
     */
    public function __construct()
    {
        $this->view = new ViewModel();
    }



    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init([
            \Application\Entity\Db\PieceJointe::class,
            \Application\Entity\Db\TypePieceJointe::class,
            \Application\Entity\Db\Fichier::class,
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

        if ($this->getIntervenant()->estPermanent()) {
            throw new \LogicException("Les pièces justificatives ne concernent que les intervenants extérieurs.");
        }

        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new \LogicException("L'intervenant {$this->getIntervenant()} n'a aucune donnée personnelle enregistrée.");
        }

        $typesPieceJointeStatut = $this->getPieceJointeProcess()->getTypesPieceJointeStatut();
        $piecesJointes          = $this->getPieceJointeProcess()->getPiecesJointes();
        $assertionPj            = (new PieceJointe())->setDossier($dossier); // entité transmise à l'assertion

        $this->view->setVariables([
            'intervenant'            => $this->getIntervenant(),
            'totalHeuresReelles'     => $this->getPieceJointeProcess()->getTotalHeuresReellesIntervenant(),
            'typesPieceJointeStatut' => $typesPieceJointeStatut,
            'piecesJointes'          => $piecesJointes,
            'dossier'                => $dossier,
            'assertionPj'            => $assertionPj,
            'role'                   => $this->getServiceContext()->getSelectedIdentityRole(),
            'title'                  => "Pièces justificatives <small>{$this->getIntervenant()}</small>",
        ]);

        $this->statusAction();

        return $this->view;
    }



    /**
     *
     * @return ViewModel
     */
    public function statusAction()
    {
        $this->initFilters();

        $messages = [];

        // recherche si toutes les PJ obligatoires ont été fournies
        $rule = clone $this->getServiceLocator()->get('DbFunctionRule');
        $rule
            ->setFunction("ose_workflow.pj_oblig_fournies")
            ->setIntervenant($this->getIntervenant());
        $toutesFournies = (int)$rule->execute();

        // recherche si des PJ restent à valider
        $rule = clone $this->getServiceLocator()->get('DbFunctionRule');
        $rule
            ->setFunction("ose_workflow.pj_oblig_validees")
            ->setIntervenant($this->getIntervenant());
        $toutesValidees = (int)$rule->execute();

        if (!$toutesFournies) {
            $messages['danger'][] = "Des pièces justificatives obligatoires n'ont pas été fournies.";
        } elseif ($toutesFournies && $toutesValidees) {
            $messages['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies et validées.";
        } elseif ($toutesFournies && !$toutesValidees) {
            $messages['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies.";
            $messages['danger'][]  = "Mais certaines doivent encore être validées par votre composante.";
        }

        $this->view->setVariables([
            'urlStatus' => $this->url()->fromRoute('piece-jointe/intervenant/status', [], [], true),
            'messages'  => $messages,
        ]);

        return $this->view;
    }



    /**
     * Listing des fichiers déposés pour un type de pièce jointe donné.
     *
     * @return type
     */
    public function listerAction()
    {
        $this->initFilters();

        $pj = $this->getPieceJointeProcess()->getPieceJointeFournie($this->getTypePieceJointe());

        return [
            'typePieceJointe' => $this->getTypePieceJointe(),
            'pj'              => $pj,
        ];
    }



    /**
     * Dépôt d'un nouveau fichier pour un type de pièce jointe donné.
     *
     * @return Response
     */
    public function ajouterAction()
    {
        $intervenant     = $this->getIntervenant();
        $typePieceJointe = $this->getTypePieceJointe();

        $result = $this->uploader()->upload();

        if ($result instanceof JsonModel) {
            return $result;
        }
        if (is_array($result)) {
            $this->getServicePieceJointe()->ajouterFichiers($result['files'], $intervenant, $typePieceJointe);
        }

        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', [], [], true);
    }



    /**
     * Téléchargement d'un fichier.
     *
     * @throws UnAuthorizedException
     */
    public function telechargerAction()
    {
        $pj      = $this->getPieceJointe();
        $fichier = $this->getFichier();

        if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_TELECHARGER)) {
            throw new UnAuthorizedException("Interdit!");
        }

        $this->uploader()->download($fichier);
    }



    /**
     * Suppression d'un fichier déposé.
     *
     * NB: la pièce jointe est supprimée s'il ne reste plus aucun fichier déposé.
     *
     * @return Response
     * @throws UnAuthorizedException
     */
    public function supprimerAction()
    {
        if (!$this->getRequest()->isPost()) {
            return $this->redirect()->toRoute('home');
        }

        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);

        if ($fichier) {
            $this->getServicePieceJointe()->supprimerFichier($fichier, $pj);
        }

        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }



    public function validerAction()
    {
        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);

        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_VALIDER)) {
                throw new UnAuthorizedException('Validation du fichier suivant interdite : ' . $fichier);
            }
            $this->getServicePieceJointe()->validerFichier($fichier, $pj, $this->getIntervenant());
        } else {
            if (!$this->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_VALIDER)) {
                throw new UnAuthorizedException('Validation de la pièce justificative suivante interdite : ' . $pj);
            }
            $this->getServicePieceJointe()->valider($pj, $this->getIntervenant());

            return $this->redirect()->toRoute('piece-jointe/intervenant', [], [], true);
        }

        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }



    public function devaliderAction()
    {
        $pj      = $this->getPieceJointe();
        $tpj     = $pj->getType();
        $fichier = $this->getFichier(false);

        if ($fichier) {
            if (!$this->isAllowed($fichier, FichierAssertion::PRIVILEGE_DEVALIDER)) {
                throw new UnAuthorizedException('Dévalidation du fichier suivant interdite : ' . $fichier);
            }
            $this->getServicePieceJointe()->devaliderFichier($fichier, $pj);
        } else {
            if (!$this->isAllowed($pj, PieceJointeAssertion::PRIVILEGE_DEVALIDER)) {
                throw new UnAuthorizedException('Dévalidation de la pièce jointe suivante interdite : ' . $pj);
            }
            $this->getServicePieceJointe()->devalider($pj);

            return $this->redirect()->toRoute('piece-jointe/intervenant', [], [], true);
        }

        return $this->redirect()->toRoute('piece-jointe/intervenant/lister', ['typePieceJointe' => $tpj->getId()], [], true);
    }



    /**
     *
     * @return ViewModel
     */
    public function voirAction()
    {
        $pj  = $this->getPieceJointe();
        $vue = urldecode($this->params()->fromRoute('vue', $defaut = 'voir')); // vue à rendre

        if (!in_array($vue, [$defaut, 'partial/validation-bar'])) {
            $vue = $defaut;
        }

        $this->view
            ->setTemplate('application/piece-jointe/' . $vue)
            ->setVariables(['pj' => $pj]);

        return $this->view;
    }



    /**
     *
     * @return ViewModel
     */
    public function voirTypeAction()
    {
        $pj  = $this->getPieceJointeProcess()->getPieceJointeFournie($this->getTypePieceJointe());
        $vue = urldecode($this->params()->fromRoute('vue', $defaut = 'voir')); // vue à rendre

        if (!in_array($vue, [$defaut, 'partial/validation-bar'])) {
            $vue = $defaut;
        }

        $this->view
            ->setTemplate('application/piece-jointe/' . $vue)
            ->setVariables(['pj' => $pj]);

        return $this->view;
    }



    /**
     *
     * @return ViewModel
     */
    public function validationBarAction()
    {
        $pj = $this->getPieceJointe();

        $this->view->setVariables([
            'pj' => $pj,
        ]);

        return $this->view;
    }



    public function typePieceJointeStatutAction()
    {
        $qb                 = $this->em()->getRepository(\Application\Entity\Db\TypePieceJointe::class)->createQueryBuilder("tpj")
            ->select("tpj")
            ->orderBy("tpj.ordre");
        $typesPiecesJointes = $qb->getQuery()->getResult();

        $qb                  = $this->em()->getRepository(\Application\Entity\Db\StatutIntervenant::class)->createQueryBuilder("si")
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



    public function modifierTypePieceJointeStatutAction()
    {
        $type               = $this->context()->mandatory()->typePieceJointeFromRoute();
        $statut             = $this->context()->mandatory()->statutIntervenantFromRoute();
        $premierRecrutement = $this->params()->fromRoute("premierRecrutement");

        if (null === $premierRecrutement) {
            throw new \LogicException("Paramètre manquant : premierRecrutement");
        }

        $qb   = $this->em()->getRepository(\Application\Entity\Db\TypePieceJointeStatut::class)->createQueryBuilder("tpjs")
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
                ->setObligatoire(true);
        }

        $obligatoireValueOptions = [
            1 => "Obligatoire",
            0 => "Facultatif",
            2 => "Non attendu",
        ];

        if ($this->getRequest()->isPost()) {

            $obligatoire = (int)$this->params()->fromPost('obligatoire', 2);
            $seuilHetd   = (int)$this->params()->fromPost('seuil_heures');

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
            'tpjs'                    => $tpjs,
            'obligatoireValueOptions' => $obligatoireValueOptions,
        ];
    }



    public function configurationAction()
    {
        return [];
    }



    /**
     * @var Intervenant
     */
    private $intervenant;



    public function getIntervenant()
    {
        if (null == $this->intervenant) {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }

        return $this->intervenant;
    }



    /**
     * @var TypePieceJointe
     */
    private $typePieceJointe;



    public function getTypePieceJointe()
    {
        if (null == $this->typePieceJointe) {
            $this->typePieceJointe = $this->context()->mandatory()->typePieceJointeFromRoute();
        }

        return $this->typePieceJointe;
    }



    /**
     * @var PieceJointe
     */
    private $pieceJointe;



    /**
     * @return PieceJointe
     */
    public function getPieceJointe()
    {
        if (null == $this->pieceJointe) {
            $this->pieceJointe = $this->context()->mandatory()->pieceJointeFromRoute();
        }

        return $this->pieceJointe;
    }



    /**
     * @return Fichier
     */
    public function getFichier($mandatory = true)
    {
        if (null == $this->fichier) {
            $this->fichier = $this->context()->mandatory($mandatory)->fichierFromRoute();
        }

        return $this->fichier;
    }



    /**
     * @return PieceJointeProcess
     */
    private function getPieceJointeProcess()
    {
        if (null === $this->process) {
            $this->process = $this->getServiceLocator()->get('ApplicationPieceJointeProcess');
        }
        $this->process->setIntervenant($this->getIntervenant());

        return $this->process;
    }

}
