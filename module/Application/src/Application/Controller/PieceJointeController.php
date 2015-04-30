<?php

namespace Application\Controller;

use Application\Assertion\FichierAssertion;
use Application\Assertion\PieceJointeAssertion;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\IntervenantExterieur;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\TypePieceJointe;
use Application\Service\PieceJointe as PieceJointeService;
use Application\Service\Process\PieceJointeProcess;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Common\Exception\MessageException;
use Common\Exception\PieceJointe\AucuneAFournirException;
use Common\Exception\PieceJointe\PieceJointeException;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * Description of UploadController
 *
 * @method Doctrine\ORM\EntityManager               em()
 * @method Application\Controller\Plugin\Context    context()
 * @method UnicaenApp\Controller\Plugin\AppInfos    appInfos()
 * @method UnicaenApp\Controller\Plugin\Mail        mail()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeController extends AbstractActionController implements WorkflowIntervenantAwareInterface
{
    use \Application\Service\Traits\ContextAwareTrait;
    use WorkflowIntervenantAwareTrait;

    /**
     * @var string
     */
    private $title;

    /**
     * @var ViewModel
     */
    private $view;

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
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\PieceJointe',
                'Application\Entity\Db\TypePieceJointe',
                'Application\Entity\Db\Fichier',
            ],
            $this->getServiceContext()->getDateObservation()
        );
    }

    /**
     *
     * @return ViewModel
     * @throws MessageException
     */
    public function indexAction()
    {
        $this->initFilters();

        $this->title = "Pièces justificatives <small>{$this->getIntervenant()}</small>";
        $role        = $this->getServiceContext()->getSelectedIdentityRole();

        if (!$this->getIntervenant() instanceof IntervenantExterieur) {
            throw new MessageException("Les pièces justificatives ne concernent que les intervenants extérieurs.");
        }

        $dossier = $this->getIntervenant()->getDossier();
        if (!$dossier) {
            throw new MessageException("L'intervenant {$this->getIntervenant()} n'a aucune donnée personnelle enregistrée.");
        }

        $typesPieceJointeStatut = $this->getPieceJointeProcess()->getTypesPieceJointeStatut();
        $piecesJointesFournies  = $this->getPieceJointeProcess()->getPiecesJointesFournies();
        $assertionPj            = (new PieceJointe())->setDossier($dossier); // entité transmise à l'assertion

        $this->view->setVariables([
            'intervenant'            => $this->getIntervenant(),
            'totalHeuresReelles'     => $this->getPieceJointeProcess()->getTotalHeuresReellesIntervenant(),
            'typesPieceJointeStatut' => $typesPieceJointeStatut,
            'piecesJointesFournies'  => $piecesJointesFournies,
            'dossier'                => $dossier,
            'assertionPj'            => $assertionPj,
            'role'                   => $role,
            'title'                  => $this->title,
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
        $complet = (int) $rule->execute();
        if ($complet) {
            $messages['success'][] = "Toutes les pièces justificatives obligatoires ont été fournies.";
        }
        else {
            $messages['danger'][] = "Il manque des pièces justificatives obligatoires.";
        }

        // recherche si des PJ restent à valider
        $rule = clone $this->getServiceLocator()->get('DbFunctionRule');
        $rule
                ->setFunction("ose_workflow.pj_oblig_validees")
                ->setIntervenant($this->getIntervenant());
        $complet = (int) $rule->execute();
        if ($complet) {
            $messages['success'][] = "Toutes les pièces justificatives fournies ont été validées par votre composante.";
        }
        else {
            $messages['danger'][] = "Elles doivent encore être validées par votre composante.";
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

        $result  = $this->uploader()->upload();

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
            $this->getServicePieceJointe()->supprimerFichier($fichier, $pj, $this->getIntervenant());
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
        }
        else {
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
        }
        else {
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
        $vue = urldecode($this->params()->fromRoute('vue', $defaut= 'voir')); // vue à rendre

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
        $vue = urldecode($this->params()->fromRoute('vue', $defaut= 'voir')); // vue à rendre

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

    /**
     * @var IntervenantExterieur
     */
    private $intervenant;

    public function getIntervenant()
    {
        if (null == $this->intervenant) {
            $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
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
     * @var Fichier
     */
    private $fichier;

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
     * @var PieceJointeProcess
     */
    private $process;

    /**
     * @return PieceJointeProcess
     */
    private function getPieceJointeProcess()
    {
        if (null === $this->process) {
            $this->process = $this->getServiceLocator()->get('ApplicationPieceJointeProcess');
        }

        try {
            $this->process->setIntervenant($this->getIntervenant());
        }
        catch (AucuneAFournirException $exc) {
            throw new MessageException(
                    "L'intervenant {$this->getIntervenant()} n'est pas sensé fournir de pièce justificative.", null, $exc);
        }
        catch (PieceJointeException $exc) {
            throw new MessageException(
                    "Gestion des pièces justificatives impossible pour l'intervenant {$this->getIntervenant()}.", null, $exc);
        }

        return $this->process;
    }

    /**
     * @return PieceJointeService
     */
    private function getServicePieceJointe()
    {
        return $this->getServiceLocator()->get('ApplicationPieceJointe');
    }
}
