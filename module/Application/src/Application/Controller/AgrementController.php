<?php

namespace Application\Controller;

use Application\Controller\Plugin\Context;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Form\Agrement\Saisie;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Initializer\AgrementServiceAwareInterface;
use Application\Service\Initializer\AgrementServiceAwareTrait;
use Application\Service\Initializer\IntervenantServiceAwareInterface;
use Application\Service\Initializer\IntervenantServiceAwareTrait;
use Application\Service\Initializer\ServiceServiceAwareInterface;
use Application\Service\Initializer\ServiceServiceAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\View\Model\ViewModel;

/**
 * Opérations sur les agréments.
 *
 * @method EntityManager em()
 * @method Context              context()
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementController extends AbstractActionController
implements ContextProviderAwareInterface,
           AgrementServiceAwareInterface, IntervenantServiceAwareInterface, ServiceServiceAwareInterface,
           WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    use AgrementServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ServiceServiceAwareTrait;

    const ACTION_VOIR        = "voir";
    const ACTION_VOIR_STR    = "voir-str";
    const ACTION_AJOUTER     = "ajouter";
    const ACTION_AJOUTER_LOT = "ajouter-lot";
    const ACTION_MODIFIER    = "modifier";

    /**
     * @var RoleInterface
     */
    private $role;

    /**
     * @var Intervenant
     */
    private $intervenant;

    /**
     * @var Agrement
     */
    private $agrement;

    /**
     * @var TypeAgrement
     */
    private $typeAgrement;

    /**
     * @var ViewModel
     */
    private $view;

    /**
     * @var Saisie
     */
    private $formSaisie;

    /**
     * Initialisation des filtres Doctrine pour les historique.
     * Objectif : laisser passer les enregistrements passés en historique pour mettre en évidence ensuite les erreurs éventuelles
     * (services sur des enseignements fermés, etc.)
     */
    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')->init(
            [
                'Application\Entity\Db\Agrement',
                'Application\Entity\Db\TypeAgrement',
            ],
            $this->context()->getGlobalContext()->getDateObservation()
        );
    }

    /**
     * Page vide invitant à sélectionner un type d'agrément dans le menu.
     *
     * @return array
     */
    public function indexAction()
    {
        $this->intervenant = $this->context()->intervenantFromRoute();
        $this->title       = sprintf("Agréments %s", $this->intervenant ? "<small>{$this->intervenant}</small>" : null);

        return ['title' => $this->title];
    }

    /**
     * Liste des agréments d'un type donné, concernant un intervenant.
     *
     * @return ViewModel
     */
    public function listerAction()
    {
        $this->initFilters();

        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->title        = sprintf("Agrément par %s <small>%s</small>", $this->typeAgrement->toString(true), $this->intervenant);
        $messages           = [];

        $agrementFourniRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $agrementFourniRule AgrementFourniRule */
        $agrementFourniRule
                ->setIntervenant($this->intervenant)
                ->setTypeAgrement($this->typeAgrement);

        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structures = $agrementFourniRule->getStructuresEnseignement();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structures = [ null ];
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }

        /**
         * Collecte des agréments pour chaque structure
         */
        $data = [];
        foreach ($structures as $s) {
            $agrements = $agrementFourniRule->getAgrementsFournis($s);
            $agrement  = array_shift($agrements);
            if (!$agrement) {
                // instanciation d'un support de création d'agrément (utilisé pour les ACL/assertion)
                $agrement = $this->getNewEntity()->setStructure($s); /* @var $agrement Agrement */
            }
            $data[] = ['structure' => $s, 'agrement' => $agrement];
        }

        if (!$data) {
            $messages['danger'] = "Aucun enseignement n'a été trouvé.";
        }

        $this->view = new ViewModel([
            'typeAgrement' => $this->typeAgrement,
            'intervenant'  => $this->intervenant,
            'data'         => $data,
            'role'         => $this->role,
            'title'        => $this->title,
            'messages'     => $messages,
        ]);
        $this->view->setTemplate('application/agrement/lister');

        return $this->view;
    }

    /**
     * Détails d'un agrément.
     *
     * @return ViewModel
     */
    public function voirAction()
    {
        $this->agrement     = $this->context()->mandatory()->agrementFromRoute();
        $this->typeAgrement = $this->agrement->getType();

        $this->view = new ViewModel([
            'agrement'    => $this->agrement,
        ]);
        $this->view->setTemplate('application/agrement/voir');

        return $this->view;
    }

    /**
     * Détails d'un agrément recherché par l'intervenant, le type et la structure.
     *
     * @return ViewModel
     */
    public function voirStrAction()
    {
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        $structure          = $this->context()->structureFromRoute();
        $this->agrement     = $this->getAgrementForStructure($structure);

        $this->view = new ViewModel([
            'agrement'    => $this->agrement,
        ]);
        $this->view->setTemplate('application/agrement/voir');

        return $this->view;
    }

    /**
     * Saisie d'un nouvel agrément.
     *
     * @return ViewModel
     * @throws RuntimeException
     */
    public function ajouterAction()
    {
        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();

        // ce type d'agrément est-il attendu ?
        if (!$this->isTypeAgrementAttendu()) {
            throw new MessageException(sprintf("Le type d'agrément &laquo; %s &raquo; n'est pas requis.", $this->typeAgrement));
        }

        $this->getFormSaisie()->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $this->initFilters();

        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structure = $this->role->getStructure();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structure = $this->intervenant->getStructure()->getParenteNiv2();
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }

        // aucun agrément ne doit déjà exister
        $agrementFourniRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $agrementFourniRule AgrementFourniRule */
        $agrementFourniRule
                ->setIntervenant($this->intervenant)
                ->setTypeAgrement($this->typeAgrement)
                ->execute();
        if (count($agrementFourniRule->getAgrementsFournis($structure))) {
            throw new MessageException(sprintf("L'agrément &laquo; %s &raquo; a déjà été ajouté.", $this->typeAgrement));
        }

        $this->agrement = $this->getNewEntity()->setStructure($structure);

        $this->updateCommon();

        return $this->view;
    }

    /**
     * Modification d'un agrément existant.
     *
     * @return ViewModel
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->agrement     = $this->context()->mandatory()->agrementFromRoute();
        $this->typeAgrement = $this->agrement->getType();

        $this->getFormSaisie()->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

        $this->updateCommon();

        return $this->view;
    }

    /**
     * Code commun à l'ajout et modif d'agrement.
     */
    private function updateCommon()
    {
        $this->title      = sprintf("Agrément par %s <small>%s</small>", $this->typeAgrement->toString(true), $this->intervenant);
        $this->formSaisie = $this->getFormSaisie();

        $this->formSaisie->bind($this->agrement);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formSaisie->setData($data);
            if ($this->formSaisie->isValid()) {
                $this->getAgrementService()->save($this->agrement);
            }
        }

        $this->view = new ViewModel([
            'intervenant' => $this->intervenant,
            'title'       => $this->title,
            'form'        => $this->formSaisie,
            'role'        => $this->role,
        ]);
        $this->view->setTemplate('application/agrement/edit');
    }

    /**
     * Saisie d'un nouvel agrément pour plusieurs intervenants à la fois.
     *
     * @return ViewModel
     * @throws LogicException
     */
    public function ajouterLotAction()
    {
        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();

        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structure = $this->role->getStructure();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structure = null;
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }

        $this->title = sprintf("Agrément par %s <small>%s</small>", $this->typeAgrement->toString(true), $structure);

        /**
         * Recherche des intervenants concernés :
         * ce sont ceux qui sont à l'étape adéquate dans le WF.
         */
        $serviceIntervenant = $this->getIntervenantService();
        $qb = $serviceIntervenant->initQuery()[0]; /* @var $qb QueryBuilder */
        $qb
                ->join("int.wfIntervenantEtape", "p", Join::WITH, "p.courante = 1")
                ->join("p.etape", "e", Join::WITH, "e.code = :codeEtape")
                ->setParameter('codeEtape', $this->typeAgrement->getCode());
        if ($structure) {
            $qb
                    ->andWhere("p.structure = :structure")
                    ->setParameter('structure', $structure);
        }
        $intervenants = $serviceIntervenant->getList($qb);

        if ($intervenants) {
            $this->agrement   = $this->getNewEntity()->setStructure($structure);
            $this->formSaisie = $this->getFormSaisie()
                    ->setIntervenants($intervenants)
                    ->bind($this->agrement);

            if ($this->getRequest()->isPost()) {
                $data = $this->getRequest()->getPost();
                $this->formSaisie->setData($data);
                if ($this->formSaisie->isValid()) {
                    foreach ($data['intervenants'] as $id) {
                        $agrement = clone $this->agrement;
                        $agrement->setIntervenant($intervenants[$id]);

                        $this->getAgrementService()->save($agrement);
                    }
                    $this->flashMessenger()->addSuccessMessage(count($data['intervenants']) . " agrément(s) enregistré(s) avec succès.");
                    $this->redirect()->toRoute(null, [], [], true);
                }
            }

            $messages[] = sprintf("Intervenants en attente d'agrément par %s.", $this->typeAgrement->toString(true));
        }
        else {
            $this->formSaisie = null;
            $messages['warning'] = sprintf("Aucun intervenant n'est en attente d'agrément par %s.", $this->typeAgrement->toString(true));
        }

        $this->view = new ViewModel([
            'intervenants' => $intervenants,
            'typeAgrement' => $this->typeAgrement,
            'title'        => $this->title,
            'form'         => $this->formSaisie,
            'role'         => $this->role,
            'messages'     => $messages,
        ]);

        return $this->view;
    }

    /**
     * Indique si le type d'agrément courant est requis ou non.
     *
     * @return boolean
     */
    private function isTypeAgrementAttendu()
    {
        $necessiteAgrementRule = $this->getServiceLocator()->get('NecessiteAgrementRule'); /* @var $necessiteAgrementRule NecessiteAgrementRule */
        $estAttendu = $necessiteAgrementRule
                ->setIntervenant($this->intervenant)
                ->setTypeAgrement($this->typeAgrement)
                ->execute();

        return $estAttendu;
    }

    /**
     * Recherche d'un agrément par l'intervenant, le type et la structure.
     *
     * @param Structure|null $structure
     * @return Agrement
     */
    private function getAgrementForStructure(Structure $structure = null)
    {
        if (!$structure && !$this->typeAgrement->isConseilAcademique()) {
            throw new LogicException(sprintf("Une structure doit être spécifiée pour le type d'agrément '%s'.", $this->typeAgrement));
        }

        $this->initFilters();

        $service = $this->getAgrementService();

        $qb = $service->finderByType($this->typeAgrement);
        if ($structure) {
            $service->finderByStructure($structure, $qb);
        }
        $service->finderByIntervenant($this->intervenant, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Instanciation d'un nouvel Agrement initialisé.
     *
     * @return Agrement
     */
    private function getNewEntity()
    {
        $agrement = $this->getAgrementService()->newEntity(); /* @var $agrement Agrement */
        $agrement
                ->setType($this->typeAgrement)
                ->setIntervenant($this->intervenant);
        return $agrement;
    }

    /**
     * Retourne le formulaire d'édition d'un agrément.
     *
     * @return Saisie
     */
    private function getFormSaisie()
    {
        if (null === $this->formSaisie) {
            $this->formSaisie = $this->getServiceLocator()->get('FormElementManager')->get('AgrementSaisieForm');
        }

        return $this->formSaisie;
    }
}