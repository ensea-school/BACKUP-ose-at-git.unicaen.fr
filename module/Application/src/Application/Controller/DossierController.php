<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Listener\DossierListener;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Parametre;

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
            $validation = $dossierValide->getValidation();
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
                $notified = $this->notify($this->intervenant);
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
    
//    /**
//     * 
//     * @return \Zend\View\Model\ViewModel
//     * @throws \Common\Exception\MessageException
//     */
//    public function piecesJointesAction()
//    { 
//        $role = $this->getContextProvider()->getSelectedIdentityRole();
//        
//        if ($role instanceof ComposanteRole) {
//            return $this->modifierPiecesJointesAction();
//        }
//        else {
//            return $this->voirPiecesJointesAction();
//        }
//    }
//    
//    /**
//     * 
//     * @return \Zend\View\Model\ViewModel
//     * @throws \Common\Exception\MessageException
//     */
//    public function voirPiecesJointesAction()
//    { 
//        $this->commonPiecesJointes();
//        
//        $this->title = "Liste des pièces justificatives à joindre <small>$this->intervenant</small>";
//        $this->form
//                ->remove('submit')
//                ->get('pj')->setAttribute('disabled', true)->setLabel("Merci d'adresser les pièces justificatives suivantes à l'adresse ci-après...");
//        
//        $this->view
//                ->setTemplate('application/dossier/pieces-jointes')
//                ->setVariables(array('title' => $this->title));
//        
//        return $this->view;
//    }
//    
//    /**
//     * 
//     * @return \Zend\View\Model\ViewModel
//     * @throws \Common\Exception\MessageException
//     */
//    public function modifierPiecesJointesAction()
//    { 
//        $this->commonPiecesJointes();
//
//        $this->title = "Checklist des pièces à joindre <small>$this->intervenant</small>";
//        $this->form->get('pj')->setLabel("Cochez les pièces qui ont été fournies...");
//        
//        if ($this->getRequest()->isPost()) {
//            $data = $this->getRequest()->getPost();
//            $this->form->setData($data);
//            if ($this->form->isValid()) {
//                $this->process->updatePiecesJointes($data['pj']);
//                $this->flashMessenger()->addSuccessMessage("Checklist enregistrée avec succès.");
//                return $this->redirect()->toUrl($this->url()->fromRoute(null, array(), array(), true));
//            }
//        }
//        
//        $this->view
//                ->setTemplate('application/dossier/pieces-jointes')
//                ->setVariables(array('title' => $this->title));
//
//        return $this->view;
//    }
//    
//    private function commonPiecesJointes()
//    {
//        $role               = $this->getContextProvider()->getSelectedIdentityRole();
//        $serviceService     = $this->getServiceService();
//        $servicePieceJointe = $this->getPieceJointeService();
//        $this->intervenant  = $this->context()->mandatory()->intervenantFromRoute();
//        
//        $servicePieceJointe->canAdd($this->intervenant, true);
//        
//        $this->dossier     = $this->intervenant->getDossier();
//        $this->process     = $this->getPieceJointeProcess();
//        try {
//            $this->process->setIntervenant($this->intervenant);
//        }
//        catch (\Common\Exception\PieceJointe\AucuneAFournirException $exc) {
//            throw new \Common\Exception\MessageException(
//                    "L'intervenant $this->intervenant n'est pas sensé fournir de pièce justificative.", null, $exc);
//        }
//        catch (\Common\Exception\PieceJointe\PieceJointeException $exc) {
//            throw new \Common\Exception\MessageException(
//                    "Gestion des pièces justificatives impossible pour l'intervenant $this->intervenant.", null, $exc);
//        }
//        $this->form = $this->process->getFormPiecesJointes();
//
//        if (!$this->dossier) {
//            throw new \Common\Exception\MessageException("L'intervenant $this->intervenant n'a aucune donnée personnelle enregistrée.");
//        }
//        
//        $piecesJointesFournies = new \Application\Rule\Intervenant\PiecesJointesFourniesRule(
//                $this->intervenant, $this->getServiceTypePieceJointeStatut());
//        $complet = $piecesJointesFournies->execute();
//        
//        $this->view = new \Zend\View\Model\ViewModel(array(
//            'intervenant'        => $this->intervenant,
//            'totalHeuresReelles' => $serviceService->getTotalHeuresReelles($this->intervenant),
//            'dossier'            => $this->dossier,
//            'complet'            => $complet,
//            'destinataires'      => $this->getDestinatairesPiecesJointes(),
//            'form'               => $this->form,
//            'role'               => $role,
//        ));
//    }
//
//    public function joindreAction()
//    {
//        $form     = $this->getFormJoindre();
//
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            // Make certain to merge the files info!
//            $post = array_merge_recursive(
//                $request->getPost()->toArray(),
//                $request->getFiles()->toArray()
//            );
//            
//            var_dump($post);
//            
//            $form->setData($post);
//            if ($form->isValid()) {
//                $data = $form->getData();var_dump('valid');
//                // Form is valid, save the form!
//                return $this->redirect()->toRoute('upload-form/success');
//            }
//        }
//        
//        $viewModel = new \Zend\View\Model\ViewModel();
//        $viewModel//->setTemplate('closer-module/ligne/joindre')
//                ->setVariables(array(
//                    'form'      => $form,
//                    'terminal'  => $this->getRequest()->isXmlHttpRequest(),
//                    'uploadUrl' => $this->url()->fromRoute(null, ['action' => 'download'], [], true),
//                ));
//
//        return $viewModel;
//    }
//    
//    public function uploadAction()
//    {
//        error_reporting(E_ALL | E_STRICT);
//        $this->uploader()
////                ->setUploadDir($this->getUploadDir($ligne))
//                ->setUploadUrl($this->getUploadUrl() . '/')
//                ->setDownloadUrl($this->getDownloadUrl())
//                ->handle();
//        exit;
//    }
//    
//    public function downloadAction()
//    {
//        error_reporting(E_ALL | E_STRICT);
//        $this->uploader()
////                ->setUploadDir($this->getUploadDir($ligne))
//                ->setUploadUrl($this->getUploadUrl() . '/')
//                ->setDownloadUrl($this->getDownloadUrl())
//                ->handle();
//        exit;
//    }
//
//    protected $formJoindre;
//    
//    protected function getFormJoindre()
//    {
//        if (null === $this->formJoindre) {
//            $this->formJoindre = new \Application\Form\Joindre();
//            $this->formJoindre//->setHydrator(HydratorFactory::getHydrator($ligne))
//                    //->bind($ligne)
//                    ->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
//        }
//        return $this->formJoindre;
//    }
//    
//    protected function getDownloadUrl()
//    {
//        return $this->url()->fromRoute(null, ['action' => 'download'], [], true);
//    }
//    
//    protected function getUploadUrl()
//    {
//        return $this->url()->fromRoute(null, ['action' => 'upload'], [], true);
//    }
//    
////    protected function getUploadDir(Ligne $ligne)
////    {
////        $options = $this->getServiceLocator()->get('closer-module_options'); /* @var $options \CloserModule\Options\ModuleOptions */
////        return sprintf($options->getUploadDir() . '/acteur-%s/ligne-%s/', 
////                $ligne->getActeur()->getIdInterne(), 
////                $ligne->getId());
////    }
//    
//    /**
//     * @return array
//     */
//    private function getDestinatairesPiecesJointes()
//    {
//        $template      = '<a href="mailto:%s">%s</a>';
//        $destinataires = [];
//        
//        if (($contactPj = $this->intervenant->getStructure()->getContactPj())) {
//            foreach (explode(',', $contactPj) as $mail) {
//                $destinataires[] = sprintf($template, $mail = trim($mail), $mail);
//            }
//        }
//        else {
//            foreach ($this->process->getRolesDestinatairesPiecesJointes() as $r) {
//                $mailto = sprintf($template, $mail = $r->getPersonnel()->getEmail(), $mail);
//                $destinataires[] = sprintf("%s : %s", $r->getPersonnel(), $mailto);
//            }
//        }
//        
//        return $destinataires;
//    }
//    
//    /**
//     * @return \Application\Service\TypePieceJointeStatut
//     */
//    private function getServiceTypePieceJointeStatut()
//    {
//        return $this->getServiceLocator()->get('ApplicationTypePieceJointeStatut');
//    }
    
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
//    
//    /**
//     * @return \Application\Service\Process\PieceJointeProcess
//     */
//    private function getPieceJointeProcess()
//    {
//        return $this->getServiceLocator()->get('ApplicationPieceJointeProcess');
//    }
//    
//    /**
//     * @return \Application\Service\PieceJointe
//     */
//    private function getPieceJointeService()
//    {
//        return $this->getServiceLocator()->get('ApplicationPieceJointe');
//    }
    
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
//    private function getServiceService()
//    {
//        return $this->getServiceLocator()->get('ApplicationService');
//    }
    
    /**
     * @return \Application\Service\Parametres
     */
    private function getParametreService()
    {
        return $this->getServiceLocator()->get('ApplicationParametres');
    }
}
