<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Listener\DossierListener;
use Application\Acl\ComposanteDbRole;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Traits\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\TypeValidation;

/**
 * Description of DossierController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class DossierController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var \Application\Entity\Db\IntervenantExterieur
     */
    private $intervenant;
    
    /**
     * @var \Application\Entity\Db\Dossier
     */
    private $dossier;
    
    /**
     * @var \Application\Service\Process\PieceJointeProcess
     */
    private $process;
    
    /**
     * @var \Zend\Form\Form
     */
    private $form;
    
    /**
     * @var string
     */
    private $title;
    
    /**
     * @var bool
     */
    private $readonly = false;
    
    /**
     * @var \Zend\View\Model\ViewModel
     */
    private $view;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirAction()
    {
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
        $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
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
            $this->intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        }
     
        $validation = null;
        $dossierValide = new \Application\Rule\Intervenant\DossierValideRule($this->intervenant);
        $dossierValide->setTypeValidation($this->getTypeValidationDossier());
        if ($dossierValide->isRelevant() && $dossierValide->execute()) {
            $this->readonly = true;
            $validation = $dossierValide->getValidation();
        }
        
        $wf    = $this->getWorkflowIntervenant($this->intervenant); /* @var $wf \Application\Service\Workflow\AbstractWorkflow */
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
                $notified = $this->notify($this->intervenant);
                $this->em()->persist($this->intervenant);
                $this->em()->flush();
                $this->flashMessenger()->addSuccessMessage("Données personnelles enregistrées avec succès.");
                if ($notified) {
                    $this->flashMessenger()->addInfoMessage(
                            "Un mail doit être envoyé pour informer la composante de la modification des données personnelles...");
                }
                
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
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function piecesJointesAction()
    { 
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof ComposanteDbRole) {
            return $this->modifierPiecesJointesAction();
        }
        else {
            return $this->voirPiecesJointesAction();
        }
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function voirPiecesJointesAction()
    { 
        $this->commonPiecesJointes();
        
        $this->title = "Liste des pièces justificatives à joindre <small>$this->intervenant</small>";
        $this->form
                ->remove('submit')
                ->get('pj')->setAttribute('disabled', true)->setLabel("Merci d'adresser les pièces justificatives suivantes à l'adresse ci-après...");
        
        $this->view
                ->setTemplate('application/dossier/pieces-jointes')
                ->setVariables(array('title' => $this->title));
        
        return $this->view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @throws \Common\Exception\MessageException
     */
    public function modifierPiecesJointesAction()
    { 
        $this->commonPiecesJointes();

        $this->title = "Checklist des pièces à joindre <small>$this->intervenant</small>";
        $this->form->get('pj')->setLabel("Cochez les pièces qui ont été fournies...");
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $this->form->setData($data);
            if ($this->form->isValid()) {
                $this->process->updatePiecesJointes($data['pj']);
                $this->flashMessenger()->addSuccessMessage("Checklist enregistrée avec succès.");
                return $this->redirect()->toUrl($this->url()->fromRoute(null, array(), array(), true));
            }
        }
        
        $this->view
                ->setTemplate('application/dossier/pieces-jointes')
                ->setVariables(array('title' => $this->title));

        return $this->view;
    }
    
    private function commonPiecesJointes()
    {
        $role               = $this->getContextProvider()->getSelectedIdentityRole();
        $serviceService     = $this->getServiceService();
        $servicePieceJointe = $this->getPieceJointeService();
        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute('id');
        
        $servicePieceJointe->canAdd($this->intervenant, true);
//        if (!$this->intervenant instanceof \Application\Entity\Db\IntervenantExterieur) {
//            throw new \Common\Exception\MessageException("La gestion de pièce justificative n'est possible que pour un vacataire non-BIATSS.");
//        }
        
        $this->dossier     = $this->intervenant->getDossier();
        $this->process     = $this->getPieceJointeProcess();
        try {
            $this->process->setIntervenant($this->intervenant);
        }
        catch (\Common\Exception\PieceJointe\AucuneAFournirException $exc) {
            throw new \Common\Exception\MessageException(
                    "L'intervenant $this->intervenant n'est pas sensé fournir de pièce justificative.", null, $exc);
        }
        catch (\Common\Exception\PieceJointe\PieceJointeException $exc) {
            throw new \Common\Exception\MessageException(
                    "Gestion des pièces justificatives impossible pour l'intervenant $this->intervenant.", null, $exc);
        }
        $this->form = $this->process->getFormPiecesJointes();

        if (!$this->dossier) {
            throw new \Common\Exception\MessageException("L'intervenant $this->intervenant n'a aucune donnée personnelle enregistrée.");
        }
        
        $this->view = new \Zend\View\Model\ViewModel(array(
            'intervenant'        => $this->intervenant,
            'totalHeuresReelles' => $serviceService->getTotalHeuresReelles($this->intervenant),
            'dossier'            => $this->dossier,
            'destinataires'      => $this->process->getRolesDestinatairesPiecesJointes(),
            'form'               => $this->form,
            'role'               => $role,
        ));
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
     * @return \Application\Service\Process\PieceJointeProcess
     */
    private function getPieceJointeProcess()
    {
        return $this->getServiceLocator()->get('ApplicationPieceJointeProcess');
    }
    
    /**
     * @return \Application\Service\PieceJointe
     */
    private function getPieceJointeService()
    {
        return $this->getServiceLocator()->get('ApplicationPieceJointe');
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
    
    /**
     * @return \Application\Service\Service
     */
    private function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }
}