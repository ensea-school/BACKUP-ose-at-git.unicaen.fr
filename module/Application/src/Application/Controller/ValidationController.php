<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Acl\ComposanteRole;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Structure;
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
     * @var \Application\Entity\Db\Service[]
     */
    private $services;

    /**
     * @var \Application\Entity\Db\Validation[]
     */
    private $validations;
    
    /**
     * @var \Application\Entity\Db\Validation[]
     */
    private $validation;
    
    /**
     * @var boolean
     */
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
     * @var ServiceValidation
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
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteRole) {
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
        
        $this->view->formModifier = $this->getDossierModifierViewModel()->form;
        
        return $this->view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    private function getDossierModifierViewModel()
    {
        $controller       = 'Application\Controller\Dossier';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'modifier';
        $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */

        return $viewModel;
    }
    
    private function commonDossier()
    {
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceValidation = $this->getServiceValidation();
        $typeValidation    = TypeValidation::CODE_DONNEES_PERSO_PAR_COMP;
        
        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->formValider = $this->getFormDossier()->setIntervenant($this->intervenant)->init();
        
        $this->em()->getFilters()->enable('historique');
        
        $qb = $serviceValidation->finderByType($typeValidation);
        $this->validation = $serviceValidation->finderByIntervenant($this->intervenant, $qb)->getQuery()->getOneOrNullResult();
        if (!$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation);
            $this->validation->setIntervenant($this->intervenant);
            if ($role instanceof ComposanteRole) {
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
        
        if ($role instanceof ComposanteRole) {
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
          
        $serviceStructure  = $this->getServiceStructure();
        $serviceService    = $this->getServiceService();
        $role              = $this->getContextProvider()->getSelectedIdentityRole();
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title       = "Validation des enseignements <small>$this->intervenant</small>";
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $typeValidation    = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP)->getQuery()->getOneOrNullResult();
        $messages          = [];
        
        $this->em()->getFilters()->enable('historique');
        
        /**
         * La validation des services d'un vacataire se fait par chaque structure d'intervention
         */
        if ($this->intervenant instanceof \Application\Entity\Db\IntervenantExterieur) {
            $messages[] = "La validation des enseignements d'un intervenant vacataire se fait par chaque composante d'enseignement.";
            // fetch des structures d'intervention
            $qb = $serviceStructure->initQuery()[0];
            $serviceStructure->finderByEnseignement($qb);
            $serviceStructure->join($serviceService, $qb, 'service');
            $serviceService->finderByContext($qb);
            $structures = $serviceStructure->getList($qb);
        }
        /**
         * La validation des services d'un permanent se fait par la structure d'affectation
         */
        else {
            $messages[] = "La validation des enseignements d'un intervenant permanent se fait par sa structure d'affectation administrative.";
            $structure = $this->intervenant->getStructure()->getParenteNiv2();
            $structures = [ $structure->getId() => $structure ];
        }

        /**
         * Collecte des validations de services pour chaque structure d'intervention
         */
        $data = array();
        foreach ($structures as $structure) {
            $data[$structure->getId()]['structure']   = $structure;
            $data[$structure->getId()]['validations'] = [];
            $data[$structure->getId()]['services']    = [];
            
            $this->collectValidationsServices($typeValidation, $structure);
            foreach ($this->validations as $validation) {
                $data[$structure->getId()]['validations'][] = $validation;
                $data[$structure->getId()]['services']      = $this->services;
            }
        }
        
        if (!$data) {
            $messages = ["Aucune validation n'a été trouvée."];
        }

        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant'       => $this->intervenant,
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'services'          => $this->services,
            'data'              => $data,
            'role'              => $role,
            'title'             => $this->title,
            'readonly'          => $this->readonly,
            'messages'          => $messages,
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
        
        $serviceService      = $this->getServiceService();
        $serviceValidation   = $this->getServiceValidation();
        $role                = $this->getContextProvider()->getSelectedIdentityRole();
        $this->intervenant   = $this->context()->mandatory()->intervenantFromRoute();
        $this->formValider   = $this->getFormValidationService()->setIntervenant($this->intervenant)->init();
        $this->title         = "Validation des enseignements <small>$this->intervenant</small>";
        $structureAffect     = $this->intervenant->getStructure()->getParenteNiv2();
        $typeVolumeHoraire   = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $typeValidation      = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP)->getQuery()->getOneOrNullResult();
        $structureValidation = $role->getStructure();
        $servicesNonValides  = [];
        $messages            = [];

        $this->em()->getFilters()->enable('historique');
        
        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);
        
        // NB : les enseignements d'un permanant sont validés par la seule composante d'affectation ;
        //    : les enseignements d'un vacataire sont validés par chaque composante d'intervenation.
        $structureEns = $this->intervenant->estPermanent() ? null : $structureValidation;
        
        if ($this->intervenant->estPermanent()) {
            if ($role->getStructure() !== $structureAffect) {
                $this->readonly = true;
                $structureValidation = $structureAffect;
                $messages['warning'] = "Les enseignements de cet intervenant permanent ne peuvent être "
                        . "validés que par la structure &laquo; $structureValidation &raquo;.";
            }
        }
        
        $this->collectValidationsServices($typeValidation, $structureEns, $structureValidation);
        
        $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes sur Service !
        
        // recherche des enseignements de l'intervenant non encore validés
        $qb = $serviceService->finderServicesNonValides($this->intervenant, $structureEns);
        $servicesNonValides = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
        $serviceService->setTypeVolumehoraire($servicesNonValides, $typeVolumeHoraire);

        if (!count($servicesNonValides)) {
            $this->validation = current($this->validations);
            $message = sprintf("Aucun enseignement à valider%s n'a été trouvé.", 
                    $structureEns ? " concernant la composante d'intervention &laquo; $structureEns &raquo;" : null);
            $messages[] = $message;
        }
        
        if (count($servicesNonValides) && !$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($structureValidation);
            
            $this->validations = [$this->validation->getId() => $this->validation] + $this->validations;
            $this->services    = [$this->validation->getId() => $servicesNonValides] + $this->services;
            
            $this->formValider->bind($this->validation);
            
            $messages[] = "Des enseignements à valider ont été trouvés...";
        }

        $this->view = new \Zend\View\Model\ViewModel(array(
            'role'              => $role,
            'typeVolumeHoraire' => $typeVolumeHoraire,
            'intervenant'       => $this->intervenant,
            'validations'       => $this->validations,
            'services'          => $this->services,
            'formValider'       => $this->formValider,
            'title'             => $this->title,
            'readonly'          => $this->readonly,
            'messages'          => $messages,
        ));
        $this->view->setTemplate('application/validation/service');
        
        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->formValider->setData($data);
            if ($this->formValider->isValid()) {
                // peuplement de la nouvelle validation avec les volumes horaires non validés
                foreach ($servicesNonValides as $s) { /* @var $s \Application\Entity\Db\Service */
                    foreach ($s->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                        $this->validation->addVolumeHoraire($vh);
                    }
                }
                $serviceValidation->save($this->validation);
                $this->flashMessenger()->addSuccessMessage("Validation enregistrée avec succès.");
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return $this->view;
    }
    
    /**
     * 
     * @param TypeValidation $typeValidation
     * @param Structure $structureEns
     * @param Structure $structureValidation
     * @return \Application\Controller\ValidationController
     */
    public function collectValidationsServices(
            TypeValidation $typeValidation, 
            Structure $structureEns = null, 
            Structure $structureValidation = null)
    {
        $serviceService = $this->getServiceService();
        $serviceValidation = $this->getServiceValidation();
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        
        $this->services = [];
        $this->validations = [];
        
        // recherche des enseignements de l'intervenant déjà validés par la composante d'affectation
        // mais n'ayant pas fait l'objet d'un contrat/avenant
        $qb = $serviceValidation->finderValidationsServices($typeValidation, $this->intervenant, $structureEns, $structureValidation);
        $validationsServices = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
        foreach ($validationsServices as $validation) { /* @var $validation \Application\Entity\Db\Validation */
            
            $this->em()->clear('Application\Entity\Db\Service'); // INDISPENSABLE entre 2 requêtes concernant les services !
            
            $qb = $serviceService->finderServicesValides($validation, $this->intervenant, $structureEns, $structureValidation);
            $servicesValides = $qb->getQuery()/*->setHint(\Doctrine\ORM\Query::HINT_REFRESH, true)*/->getResult();
            $serviceService->setTypeVolumehoraire($servicesValides, $typeVolumeHoraire);
            
            $this->validations[$validation->getId()] = $validation;
            $this->services[$validation->getId()]    = $servicesValides;
        }
//        var_dump(count($validationsServices) . " validations tourvées :");
//        foreach ($validations as $id => $validation) {
//            $ss = $services[$id];
//            var_dump(count($ss) . " services associés.");
//            foreach ($ss as $s) {
//                var_dump(count($s->getVolumeHoraire()) . " volumes associés :", \UnicaenApp\Util::collectionAsOptions($s->getVolumeHoraire()));
//            }
//        }
        
        return $this;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function contratAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteRole) {
            return $this->modifierContratAction();
        }
        else {
            throw new \Common\Exception\LogicException("Non implémenté.");
        }
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
            $softDelete = true;
            // NB: une validation de contrat doit être supprimée! Il existe une relation ManyToOne de Contrat vers Validation
            // et si la validation est seulement historisée, Doctrine ne trouve plus la Validation référencée dans Contrat 
            // (EntityNotFoundException) si le filtre 'historique' est actif.
            if ($validation->getTypeValidation()->getCode() === TypeValidation::CODE_CONTRAT_PAR_COMP) {
                $softDelete = false;
            }
            
            $errors = array();
            try {
                $this->getServiceValidation()->delete($validation, $softDelete);
                $this->flashMessenger()->addSuccessMessage("Validation <strong>supprimée</strong> avec succès.");
            }
            catch(\Exception $e){
                $e = \Application\Exception\DbException::translate($e);
                $errors[\UnicaenApp\View\Helper\Messenger::ERROR] = $e->getMessage();
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
     * @return \Application\Service\TypeVolumeHoraire
     */
    private function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationTypeVolumeHoraire');
    }
    
    /**
     * @return \Application\Service\TypeValidation
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}
