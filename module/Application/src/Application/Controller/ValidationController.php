<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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

    private $validation;
    private $readonly = false;
    
    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;
    
    /**
     * @var \Zend\Form\Form
     */
    private $form;
    
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
        
        $this->form->get('valide')->setLabel("Si cette case est cochée, cela indique que vos données personnelles ont été validées...");
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
        
        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
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
        $this->intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        $typeValidation    = TypeValidation::CODE_DONNEES_PERSO_PAR_COMP;
        
        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->form = $this->getFormDossier()->setIntervenant($this->intervenant)->init();
        
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
            $this->form->get('valide')->setValue(true);
        }
        $this->form->bind($this->validation);
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'validation'  => $this->validation,
            'form'        => $this->form,
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
        $this->intervenant    = $this->context()->mandatory()->intervenantFromRoute('id');
        $this->form           = $this->getFormService()->setIntervenant($this->intervenant)->init();
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
     * (Dé)Validation des enseignements.
     * NB : une validation par composante d'intervention.
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws RuntimeException
     */
    public function modifierServiceAction()
    {
        $this->readonly = false;
        
        $serviceValidation    = $this->getServiceValidation();
        $serviceVolumeHoraire = $this->getServiceVolumeHoraire();
        $role                 = $this->getContextProvider()->getSelectedIdentityRole();
        $structure            = $role->getStructure();
        $this->intervenant    = $this->context()->mandatory()->intervenantFromRoute('id');
        $this->form           = $this->getFormService()->setIntervenant($this->intervenant)->init();
        $this->title          = "Validation des enseignements au sein de la structure '$structure' <small>$this->intervenant</small>";
        $typeValidation       = TypeValidation::CODE_SERVICES_PAR_COMP;
        
        $serviceValidation->canAdd($this->intervenant, $typeValidation, true);

        $this->em()->getFilters()->enable('historique');
        
        $qb = $serviceValidation->finderByType($typeValidation);
        $qb = $serviceValidation->finderByIntervenant($this->intervenant, $qb);
        $this->validation = $serviceValidation->finderByStructureIntervention($structure, $qb)->getQuery()->getOneOrNullResult();
        if (!$this->validation) {
            $this->validation = $serviceValidation->newEntity($typeValidation)
                    ->setIntervenant($this->intervenant)
                    ->setStructure($structure);
            $qb = $serviceVolumeHoraire->finderByIntervenant($this->intervenant);
            $volumesHoraires = $serviceVolumeHoraire->finderByStructureIntervention($structure, $qb)->getQuery()->getResult();
            foreach ($volumesHoraires as $volumeHoraire) {
                $this->validation->addVolumeHoraire($volumeHoraire);
            }
        }
        else {
            $this->form->get('valide')->setValue(true);
        }
        $this->form->bind($this->validation);
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'form'        => $this->form,
            'role'        => $role,
            'title'       => $this->title,
            'readonly'    => $this->readonly,
        ));
        $this->view->setTemplate('application/validation/service');
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $valide = (bool) $data['valide'];
                $this->updateValidation($valide);
                
                return $this->redirect()->toRoute(null, array(), array(), true);
            }
        }
        
        return $this->view;
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
        if (null === $this->form) {
            $this->form = new DossierValidation();
        }
        
        return $this->form;
    }
    
    /**
     * @return ServiceValidation
     */
    protected function getFormService()
    {
        if (null === $this->form) {
            $this->form = new ServiceValidation();
        }
        
        return $this->form;
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
