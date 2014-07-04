<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\ORM\Query\Expr\Join;
use Common\Exception\RuntimeException;
use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\TypeValidation;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Form\Intervenant\DossierValidation;
use Application\Form\Intervenant\ServiceValidation;

/**
 * Description of ValidationController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * @var \Application\Entity\Db\Validation[]
     */
    private $validation;
    
    private $readonly = false;
    
    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;
    
    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;
    
    /**
     * @var \Zend\Form\Form
     */
    private $formValider;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var \Zend\View\Model\ViewModel
     */
    private $view;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function voirAction()
    {
        $role                 = $this->getContextProvider()->getSelectedIdentityRole();
        $this->validation     = $this->context()->mandatory()->validationFromRoute(); /* @var $validation \Application\Entity\Db\Validation */
        $this->intervenant    = $this->validation->getIntervenant();
        $this->title          = "Détails d'une validation";
        
        // enseignements concernés
        $service = $this->getServiceService();
        $qb = $service->finderByIntervenant($this->intervenant);
        $qb = $service->finderByValidation($this->validation, $qb);
        $services = $service->getList($qb);
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'validation' => $this->validation,
            'services'   => $services,
            'role'       => $role,
            'title'      => $this->title,
        ));
        $this->view->setTemplate('application/validation/voir');
        
        return $this->view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function listeAction()
    {
        $role                 = $this->getContextProvider()->getSelectedIdentityRole();
        $typeValidation       = $this->context()->mandatory()->typeValidationFromRoute();
        $this->intervenant    = $this->context()->mandatory()->intervenantFromRoute();
        $this->title          = (string) $typeValidation;
        
        $qb = $this->getServiceValidation()->finderByType($typeValidation);
        $qb = $this->getServiceValidation()->finderByIntervenant($this->intervenant, $qb);
        $qb = $this->getServiceValidation()->finderByStructureIntervention($role->getStructure(), $qb);
        $this->validation = $qb->getQuery()->getResult();
        
        // enseignements concernés par chaque validation
        $services = [];
        foreach ($this->validation as $validation) { /* @var $validation \Application\Entity\Db\Validation */
            $qb = $this->getServiceService()->finderByIntervenant($this->intervenant);
            $qb = $this->getServiceService()->finderByValidation($validation, $qb);
            $services[$validation->getId()] = $this->getServiceService()->getList($qb);
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'validations' => $this->validation,
            'services'    => $services,
            'role'        => $role,
            'title'       => $this->title,
        ));
        $this->view->setTemplate('application/validation/liste');
        
        return $this->view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function dossierAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteDbRole) {
            return $this->modifierDossierAction();
        }
        else {
            return $this->voirDossierAction();
        }
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirDossierAction()
    { 
        $this->title    = "Validation de vos données personnelles";
        $this->readonly = true;
            
        $this->commonDossier();
        
        $this->formValider->get('valide')->setLabel("Si cette case est cochée, cela indique que vos données personnelles ont été validées...");
        $this->view->setTemplate('application/validation/voir-dossier');
                
        return $this->view;
    }
    
    /**
     * (Dé)Validation des données personnelles vacataires.
     * NB : une seule validation pour toutes les composantes.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function modifierDossierAction()
    {
        $this->title    = "Validation des données personnelles <small>$this->intervenant</small>";
        $this->readonly = false;
            
        $this->commonDossier();
        
        if ($this->validation->getId()) {
            $this->formValider->get('valide')->setLabel("Décochez pour dévalider les données personnelles");
        }
        
        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                $complet = (bool) $data['valide'];
                $this->updateValidation($complet);
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return $this->view;
    }
    
    private function commonDossier()
    {
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceValidation = $this->getServiceValidation();
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $typeValidation    = TypeValidation::CODE_DONNEES_PERSO_PAR_COMP;
        
        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->formValider = $this->getFormDossier()->setIntervenant($this->intervenant)->init();
        
        $this->em()->getFilters()->enable('historique');
        
        $qb = $serviceValidation->finderByType($typeValidation);
        $this->validation = $serviceValidation->finderByIntervenant($this->intervenant, $qb)->getQuery()->getOneOrNullResult();
        if (!$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation);
            $this->validation->setIntervenant($this->intervenant);
            if ($role instanceof ComposanteDbRole) {
                $this->validation->setStructure($role->getStructure());
            }
        }
        else {
            $this->formValider->get('valide')->setValue(true);
        }
        $this->formValider->bind($this->validation);
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'validation'  => $this->validation,
            'form'        => $this->formValider,
            'role'        => $role,
            'readonly'    => $this->readonly,
            'title'       => $this->title,
        ));
        $this->view->setTemplate('application/validation/dossier');
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function serviceAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteDbRole) {
            return $this->modifierServiceAction();
        }
        else {
            return $this->voirServiceAction();
        }
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirServiceAction()
    { 
        $this->readonly = true;
          
        $serviceStructure     = $this->getServiceStructure();
        $serviceValidation    = $this->getServiceValidation();
        $serviceService       = $this->getServiceService();
        $role                 = $this->getContextProvider()->getSelectedIdentityRole();
        $this->intervenant    = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider    = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title          = "Validation des enseignements <small>$this->intervenant</small>";
        
        $this->em()->getFilters()->enable('historique');
        
        // fetch des structures d'intervenation
        $qb = $serviceStructure->initQuery()[0];
        $serviceStructure->join($serviceService, $qb, 'id', 'structureEns');
        $serviceService->finderByContext($qb);
        $structures = $serviceStructure->getList($qb);
        
        // collecte des validation de services pour chaque structure d'intervention
        $this->validation = array();
        foreach ($structures as $key => $structure) {
            $this->validation[$key] = array('validation' => null, 'structure' => $structure);
            $qb = $serviceValidation->finderByType($code = TypeValidation::CODE_SERVICES_PAR_COMP);
            $qb = $serviceValidation->finderByIntervenant($this->intervenant, $qb);
            $validation = $serviceValidation->finderByStructureIntervention($structure, $qb)->getQuery()->getOneOrNullResult();
            if ($validation) {
                $this->validation[$key]['validation'] = $validation;
            }
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'validations' => $this->validation,
            'role'        => $role,
            'title'       => $this->title,
            'readonly'    => $this->readonly,
        ));
        $this->view->setTemplate('application/validation/voir-service');
        
        return $this->view;
    }
    
    /**
     * Validation des enseignements prévisionnels par la composante d'affectation de l'intervenant.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function modifierServiceAction()
    {
        $this->readonly = false;
        
        $serviceService    = $this->getServiceService();
        $serviceValidation = $this->getServiceValidation();
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $this->structure   = $role->getStructure();
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title       = "Validation des enseignements <small>$this->intervenant</small>";
        $typeValidation    = TypeValidation::CODE_SERVICES_PAR_COMP;

        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->em()->getFilters()->enable('historique');
        
        // recherche des enseignements de l'intervenant non encore validés
        $qb = $serviceService->getRepo()->createQueryBuilder('s')
                ->addSelect("i, vh, strens")
                ->join("s.intervenant", "i")
                ->join("s.volumeHoraire", 'vh')
                ->join("s.structureEns", 'strens')
                ->leftJoin("vh.validation", 'v')
                ->leftJoin("v.typeValidation", 'tv')
                ->andWhere("i = :intervenant")->setParameter('intervenant', $this->intervenant)
                ->andWhere("tv.code = :tv")->setParameter('tv', $typeValidation)
                ->andWhere("v is null")
                ->addOrderBy("strens.libelleCourt", 'asc');
        $servicesNonValides = $serviceService->getList($qb);
        
        // recherche des enseignements de l'intervenant déjà validés par la composante d'affectation
        // mais n'ayant pas fait l'objet d'un contrat/avenant
        $qb = $serviceService->getRepo()->createQueryBuilder('s')
                ->addSelect("i, vh, strens, v, tv, s")
                ->join("s.intervenant", "i")
                ->join("s.volumeHoraire", 'vh')
                ->join("s.structureEns", 'strens')
                ->join("vh.validation", "v")
                ->join("v.typeValidation", 'tv')
                ->join("v.structure", 'str') // validés par la structure d'affectation
                ->andWhere("i = :intervenant")->setParameter('intervenant', $this->intervenant)
                ->andWhere("tv.code = :tv")->setParameter('tv', $typeValidation)
                ->andWhere("str = :structure")->setParameter('structure', $this->structure) // validés par la structure d'affectation
                ->andWhere("vh.contrat is null")
                ->orderBy("v.histoModification", 'desc')
                ->addOrderBy("strens.libelleCourt", 'asc');
//        var_dump($qb->getQuery()->getSQL(), $qb->getQuery()->getParameter('tv'));
        $servicesValides = $serviceService->getList($qb);
        // collecte des validations correspondantes et mise en forme pour la vue
        $validations = $services = [];
        foreach ($servicesValides as $service) { /* @var $service \Application\Entity\Db\Service */
            if (!($vh = $service->getVolumeHoraire()->first())) {
                throw new RuntimeException("Anomalie : service sans volume horaire.");
            }
            $validation = $vh->getValidation()->first();
            if (!$validation) {
                continue;
            }
            $validations[$validation->getId()] = $validation;
            $services[$validation->getId()][]  = $service;
        }

        if (!count($servicesNonValides)) {
            $this->validation = reset($validations);
        }
        
        if (!$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($this->structure);
            $volumesHorairesNonValides = [];
            foreach ($servicesNonValides as $s) { /* @var $s \Application\Entity\Db\Service */
                $volumesHorairesNonValides = array_merge($volumesHorairesNonValides, $s->getVolumeHoraire()->toArray());
            }
            foreach ($volumesHorairesNonValides as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                $this->validation->addVolumeHoraire($vh);
            }
            
            $validations = [$this->validation->getId() => $this->validation] + $validations;
            $services    = [$this->validation->getId() => $servicesNonValides] + $services;
            
            $this->formValider->bind($this->validation);
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant'        => $this->intervenant,
            'validations'        => $validations,
            'services'           => $services,
            'formValider'        => $this->formValider,
            'role'               => $role,
            'title'              => $this->title,
            'readonly'           => $this->readonly,
        ));
        $this->view->setTemplate('application/validation/service');
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                $valide = (bool) $data['valide'];
                $this->updateValidation($valide);
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return $this->view;
    }
    
    /**
     * 
     * @throws RuntimeException
     */
    public function supprimerAction()
    {
        $role       = $this->getContextProvider()->getSelectedIdentityRole();
        $validation = $this->context()->mandatory()->validationFromRoute(); /* @var $validation \Application\Entity\Db\Validation */
        
        if ($validation->getStructure() !== $role->getStructure()) {
            throw new RuntimeException("Suppression de la validation interdite.");
        }
        
        $title     = "Suppression de la validation";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                $this->getServiceValidation()->delete($validation);
                $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
            }
            catch(\Exception $e){
                $e = \Application\Exception\DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }
    
    /**
     * 
     * @param bool $valide
     * @return boolean
     */
    protected function updateValidation($valide)
    {
        if ($valide) {
            $this->em()->persist($this->validation);
        }
        else {
            $this->validation->setHistoDestruction(new \DateTime());
        }
        $this->em()->flush();

        $this->notify($valide);
        $this->flashMessenger()->addSuccessMessage(sprintf("Validation <strong>%s</strong> avec succès.", $valide ? "enregistrée" : "supprimée"));
//        $this->flashMessenger()->addInfoMessage("Un mail doit être envoyé pour informer l'intervenant, non ?...");
        
        return $this;
    }
    
    protected function notify($complet)
    {
        
    }
    
    /**
     * @return DossierValidation
     */
    protected function getFormDossier()
    {
        if (null === $this->formValider) {
            $this->formValider = new DossierValidation();
        }
        
        return $this->formValider;
    }
    
    /**
     * @return ServiceValidation
     */
    protected function getFormValidationService()
    {
        if (null === $this->formValider) {
            $this->formValider = new ServiceValidation();
        }
        
        return $this->formValider;
    }
    
    /**
     * @return \Application\Service\Validation
     */
    private function getServiceValidation()
    {
        return $this->getServiceLocator()->get('ApplicationValidation');
    }
    
    /**
     * @return \Application\Service\Service
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
    
    /**
     * @return \Application\Service\VolumeHoraire
     */
    private function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    }
    
    /**
     * @return \Application\Service\Structure
     */
    private function getServiceStructure()
    {
        return $this->getServiceLocator()->get('ApplicationStructure');
    }
    
    /**
     * @return \Application\Service\TypeValidation
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}
