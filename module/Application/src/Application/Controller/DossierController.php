<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Listener\DossierListener;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\TypeValidation;

/**
 * Description of DossierController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;
    
    /**
     * @var \Zend\Form\Form
     */
    private $form;
    
    /**
     * @var bool
     */
    private $readonly = false;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $dossier     = $intervenant->getDossier();
        $title       = "Données personnelles <small>$intervenant</small>";
        $short       = $this->params()->fromQuery('short', false);
        $view        = new \Zend\View\Model\ViewModel();

        if (!$dossier) {
            throw new \Common\Exception\MessageException("L'intervenant $intervenant n'a aucune donnée personnelle enregistrée.");
        }
        
        $view->setVariables(compact('intervenant', 'dossier', 'title', 'short'));
        
        return $view;
    }
    
    /**
     * Modification du dossier d'un intervenant.
     * 
     * @return type
     * @throws RuntimeException
     */
    public function modifierAction()
    {
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $service = $this->getDossierService();
        $this->form    = $this->getFormModifier();

        if ($role instanceof IntervenantRole) {
            $this->intervenant = $role->getIntervenant();
        }
        else {
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
     
        $validation = null;
        $dossierValide = new \Application\Rule\Intervenant\DossierValideRule($this->intervenant);
        $dossierValide->setTypeValidation($this->getTypeValidationDossier());
        if ($dossierValide->isRelevant() && $dossierValide->execute()) {
            $this->readonly = true;
            $validation = $this->intervenant->getValidation($this->getTypeValidationDossier());
        }
        
        $wf    = $this->getWorkflowIntervenant()->setIntervenant($this->intervenant); /* @var $wf \Application\Service\Workflow\AbstractWorkflow */
        $step  = $wf->getNextStep($wf->getStepForCurrentRoute());
        $url   = $step ? $wf->getStepUrl($step) : $this->url('home');
        if ($role instanceof IntervenantRole) {
            $role->getIntervenant();
            $label = $step ? ' et ' . lcfirst($step->getLabel($role)) . '...' : null;
            $this->form->get('submit')->setAttribute('value', "J'enregistre" . $label);
        }
        else {
            $url = $this->url()->fromRoute(null, array(), array(), true);
        }
        
        $service->canAdd($this->intervenant, true);
        
        if (!($dossier = $this->intervenant->getDossier())) {
            $dossier = $service->newEntity()->fromIntervenant($this->intervenant);
            $this->intervenant->setDossier($dossier);
        }
        
        $this->form->bind($this->intervenant);
        
        if (!$this->readonly && $this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $this->em()->persist($dossier);
//                $notified = $this->notify($this->intervenant);
                $this->em()->persist($this->intervenant);
                $this->em()->flush();
                $this->flashMessenger()->addSuccessMessage("Données personnelles enregistrées avec succès.");
//                if ($notified) {
//                    $this->flashMessenger()->addInfoMessage(
//                            "Un mail doit être envoyé pour informer la composante de la modification des données personnelles...");
//                }
                
                return $this->redirect()->toUrl($url);
            }
        }
        
        $view = new \Zend\View\Model\ViewModel(array(
            'intervenant' => $this->intervenant,
            'form'        => $this->form,
            'validation'  => $validation,
            'readonly'    => $this->readonly,
        ));
        
        return $view;
    }
    
    /**
     * @return TypeValidation
     */
    private function getTypeValidationDossier() 
    {
        $qb = $this->getTypeValidationService()->finderByCode(TypeValidation::CODE_DONNEES_PERSO_PAR_COMP);
        
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    protected function notify(Intervenant $intervenant)
    {
        if (DossierListener::$created || DossierListener::$modified) {
            // envoyer un mail au gestionnaire
            return true;
        }
        
        return false;
    }
    
    /**
     * @return \Application\Form\Intervenant\Dossier
     */
    private function getFormModifier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('IntervenantDossier');
    }
    
    /**
     * @return \Application\Service\TypeValidation
     */
    private function getTypeValidationService()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
    
    /**
     * @return \Application\Service\Dossier
     */
    private function getDossierService()
    {
        return $this->getServiceLocator()->get('ApplicationDossier');
    }
}
