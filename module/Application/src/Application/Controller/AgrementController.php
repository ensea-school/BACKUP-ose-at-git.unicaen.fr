<?php

namespace Application\Controller;

use Application\Entity\Db\Agrement;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeAgrement;
use Application\Form\Agrement\Saisie;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Service\ContextProviderAwareInterface;
use BjyAuthorize\Exception\UnAuthorizedException;
use Common\Exception\LogicException;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Initializer\AgrementServiceAwareInterface;
use Application\Service\Initializer\AgrementServiceAwareTrait;
use Application\Service\Initializer\IntervenantServiceAwareInterface;
use Application\Service\Initializer\IntervenantServiceAwareTrait;
use Application\Service\Initializer\ServiceServiceAwareInterface;
use Application\Service\Initializer\ServiceServiceAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Opérations sur les agréments.
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method Plugin\Context              context()
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
        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
        $this->title        = sprintf("Agrément &laquo; %s &raquo; <small>%s</small>", $this->typeAgrement, $this->intervenant);
        $messages           = [];

        $this->em()->getFilters()->enable('historique');
        
        $agrementFourniRule = $this->getServiceLocator()->get('AgrementFourniRule'); /* @var $agrementFourniRule AgrementFourniRule */
        $agrementFourniRule
                ->setIntervenant($this->intervenant)
                ->setTypeAgrement($this->typeAgrement)/*
                ->execute()*/;

        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structures = $agrementFourniRule->getStructuresEnseignement();
            $messages[] = sprintf("Sont attendus autant d'agréments &laquo; %s &raquo; qu'il y a de composantes d'enseignement.", 
                    $this->typeAgrement);
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structureEns = $this->intervenant->getStructure()->getParenteNiv2();
            $structures = [ $structureEns->getId() => $structureEns];
            $messages[] = sprintf("Est attendu un seul agrément &laquo; %s &raquo; par la composante d'enseignement &laquo; %s &raquo;.", 
                    $this->typeAgrement, $structureEns);
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }

        /**
         * Collecte des agréments pour chaque structure
         */
        $data = array();
        foreach ($structures as $s) {
            $agrements = $agrementFourniRule->getAgrementsFournis($s);
            $agrement  = array_shift($agrements);
            if (!$agrement) {
                // instanciation d'un support de création d'agrément (utilisé pour les ACL/assertion)
                $agrement = $this->getNewEntity()->setStructure($s); /* @var $agrement Agrement */
            }
            $data[$s->getId()]['structure'] = $s;
            $data[$s->getId()]['agrement']  = $agrement;
        }

        if (!$data) {
            $messages['danger'] = "Aucun enseignement n'a été trouvé.";
        }

        $this->view = new ViewModel(array(
            'typeAgrement' => $this->typeAgrement,
            'intervenant'  => $this->intervenant,
            'data'         => $data,
            'role'         => $this->role,
            'title'        => $this->title,
            'messages'     => $messages,
        ));
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

        $this->view = new ViewModel(array(
            'agrement'    => $this->agrement,
        ));
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
        $structure          = $this->context()->mandatory()->structureFromRoute();
        $this->agrement     = $this->getAgrementForStructure($structure);

        $this->view = new ViewModel(array(
            'agrement'    => $this->agrement,
        ));
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
        
        $this->getFormSaisie()->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        $this->em()->getFilters()->enable('historique');

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

        $this->getFormSaisie()->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
        
        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structureEns = $this->role->getStructure();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
            $structureEns = $this->intervenant->getStructure()->getParenteNiv2();
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }

        if ($this->agrement->getStructure() !== $structureEns) {
            throw new UnAuthorizedException("Les structures ne correspondent pas!");
        }

        $this->updateCommon();

        return $this->view;
    }

    /**
     * Code commun à l'ajout et modif d'agrement.
     */
    private function updateCommon()
    {
        $this->title      = sprintf("Saisie de l'agrément &laquo; %s &raquo; <small>%s</small>", $this->typeAgrement, $this->intervenant);
        $this->formSaisie = $this->getFormSaisie();

        $this->formSaisie->bind($this->agrement);

        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formSaisie->setData($data);
            if ($this->formSaisie->isValid()) {
                if (!$this->agrement->getId()) {
                    $this->em()->persist($this->agrement);
                }
                $this->em()->flush($this->agrement);
            }
        }

        $this->view = new ViewModel(array(
            'intervenant' => $this->intervenant,
            'title'       => $this->title,
            'form'        => $this->formSaisie,
            'role'        => $this->role,
        ));
        $this->view->setTemplate('application/agrement/edit');
    }
    
    /**
     * 
     */
    public function ajouterLotAction()
    {
        $this->role         = $this->getContextProvider()->getSelectedIdentityRole();
        $this->typeAgrement = $this->context()->mandatory()->typeAgrementFromRoute();
        
        /**
         * Il y a un Conseil Restreint par structure d'enseignement
         */
        if ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_RESTREINT) {
            $structureEns = $this->role->getStructure();
        }
        /**
         * Il y a un seul Conseil Academique pour toutes les structures d'enseignement
         */
        elseif ($this->typeAgrement->getCode() === TypeAgrement::CODE_CONSEIL_ACADEMIQUE) {
//            $structureEns = $this->intervenant->getStructure()->getParenteNiv2();
            $structureEns = $this->role->getStructure();
        }
        else {
            throw new LogicException("Type d'agrément inattendu!");
        }
        
        $this->title = sprintf("Ajout d'agréments &laquo; %s &raquo; par lot <small>%s</small>", $this->typeAgrement, $structureEns);
        
        /**
         * Recherche des intervenants candidats :
         * - ayant des services sur la structure adéquate 
         * - et en attente du type d'agrément spécifié dans la requête
         */
        $serviceIntervenant = $this->getIntervenantService();
        $serviceService = $this->getServiceService();
        $qb = $serviceIntervenant->initQuery()[0]; /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb
                ->leftJoin("int.agrement", "ag")
                ->join("int.statut", "sta")
                ->join("sta.typeAgrementStatut", "tas")
                ->join("tas.type", 'ta')
                ->andWhere("ta = :type")->setParameter('type', $this->typeAgrement);
        $serviceService->finderByStructureEns($structureEns, $qb);
        $serviceService->finderByAnnee($this->getContextProvider()->getGlobalContext()->getAnnee(), $qb);
        $serviceIntervenant->join($serviceService, $qb, "service"); //print_r($qb->getQuery()->getDQL());
        $intervenantsCandidats = $serviceIntervenant->getList($qb);
        
        /**
         * Parcours des intervenants candidats pour ne retenir que ceux qui sont à l'étape agrément
         * dans leur workflow 
         */
        $intervenants = [];
        foreach ($intervenantsCandidats as $i) {
            $wf = $this->getWorkflowIntervenant();
            $wf->setIntervenant($i)->setRole($this->role);
            $step = $wf->getCurrentStep();
            if ($step instanceof \Application\Service\Workflow\Step\AgrementStep) {
                $intervenants[$i->getId()] = $i;
            }
            $wf->recreateSteps();
        }

        if ($intervenants) {
            $this->agrement   = $this->getNewEntity()->setStructure($structureEns);
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

            $messages[] = sprintf("Les intervenants ci-dessous sont en attente d'agrément &laquo; %s &raquo;.", $this->typeAgrement);
        }
        else {
            $this->formSaisie = null;
            $messages['warning'] = sprintf("Aucun intervenant en attente d'agrément &laquo; %s &raquo; n'a été trouvé.", $this->typeAgrement);
        }
        
        $this->view = new ViewModel(array(
            'intervenants' => $intervenants,
            'title'        => $this->title,
            'form'         => $this->formSaisie,
            'role'         => $this->role,
            'messages'     => $messages,
        ));
        
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
     * @param Structure $structure
     * @return Agrement
     */
    private function getAgrementForStructure(Structure $structure)
    {
        $service = $this->getAgrementService();
        
        $qb = $service->finderByType($this->typeAgrement);
        $service->finderByStructure($structure, $qb);
        $service->finderByIntervenant($this->intervenant, $qb);
        
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Recherche d'un agrément par l'intervenant, le type et la structure.
     * 
     * @param Structure $structure
     * @return Agrement
     */
    private function getIntervenantsSansAgrement(Structure $structure)
    {
        $qb = $this->getIntervenantService()->getRepo()->createQueryBuilder("i");
//        $qb->
        
        
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
                ->setIntervenant($this->intervenant)
                ->setAnnee($this->getContextProvider()->getGlobalContext()->getAnnee());

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