<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\Intervenant;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantController extends AbstractActionController implements ContextProviderAwareInterface, WorkflowIntervenantAwareInterface
{
    use ContextProviderAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Intervenant
     */
    private $intervenant;
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            // redirection selon le workflow
            $intervenant = $role->getIntervenant();
            $wf  = $this->getWorkflowIntervenant()->setIntervenant($intervenant);
            $url = $wf->getCurrentStepUrl();
            if (!$url) {
                $url = $wf->getStepUrl($wf->getLastStep());
            }
            return $this->redirect()->toUrl($url);
        }
        
        return $this->redirect()->toRoute('intervenant/rechercher');
    }
    
    public function rechercherAction()
    {
        $view = $this->choisirAction();
        
        if ($this->intervenant) {
            return $this->redirect()->toRoute('intervenant/fiche', array('intervenant' => $this->intervenant->getSourceCode()));
        }
        
        $view->setTemplate('application/intervenant/choisir');

        return $view;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function choisirAction()
    {
        $intervenant = $this->context()->intervenantFromQuery();
        
        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'intervenant concerné :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        if ($intervenant) {
            $f = new \Common\Filter\IntervenantTrouveFormatter();
            $interv->setValue($f->filter($intervenant));
        }
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array(
            'action' => $this->getRequest()->getRequestUri(),
            'class'  => 'intervenant-rech'));
        $form->add($interv);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $sourceCode = $form->get('interv')->getValueId();
                $this->getRequest()->getQuery()->set('sourceCode', $sourceCode);
                $this->intervenant = $this->importerAction()->getVariable('intervenant');
                $this->addIntervenantChoisiRecent($this->intervenant);
                if (($redirect = $this->params()->fromQuery('redirect'))) {
                    $redirect = str_replace('__sourceCode__', $sourceCode, $redirect);
                    return $this->redirect()->toUrl($redirect);
                }
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/intervenant/choisir')
                ->setVariables(array(
                    'form'    => $form, 
                    'title'   => "Rechercher un intervenant",
                    'recents' => $this->getIntervenantsChoisisRecents()));
        
        return $viewModel;
    }
    
    public function importerAction()
    {
        if (!($sourceCode = $this->params()->fromQuery('sourceCode', $this->params()->fromPost('sourceCode')))) {
            throw new LogicException("Aucun code source d'intervenant spécifié.");
        }
        
        $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->importer($sourceCode);
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant));
        return $view;
    }

    public function voirAction()
    {
//        $page = $this->params()->fromQuery('page', 'fiche');
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this->em()->getFilters()->enable('historique');

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $title = "Détails d'un intervenant";
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
//        if ('services' == $page){
//            $params = $this->getEvent()->getRouteMatch()->getParams();
//            $params['action'] = 'intervenant';
//            $params['intervenant'] = $intervenant->getSourceCode();
//            $servicesViewModel = $this->forward()->dispatch('Application\Controller\Service', $params);
//            $view->addChild($servicesViewModel, 'services');
//        }
        $view->setVariables(compact('intervenant', 'changements', 'title', 'short', 'page', 'role'));
        return $view;
    }

    public function apercevoirAction()
    {
        $this->em()->getFilters()->enable('historique');

        $intervenant = $this->context()->mandatory()->intervenantFromRoute();

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $title = "Aperçu d'un intervenant";
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'changements', 'title', 'short'));
        return $view;
    }

    public function voirHeuresCompAction()
    {
        $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        $formule = $this->getServiceLocator()->get('ProcessFormuleHetd');

        return compact('intervenant', 'formule');
    }

    public function feuilleDeRouteAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        $this->em()->getFilters()->enable('historique');

        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute();
        }
        
        if ($intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            throw new \Common\Exception\MessageException("Pas encore implémenté pour IntervenantPermanent");
        }
        
        $title = sprintf("Feuille de route <small>%s</small>", $intervenant);
        
        $wf = $this->getWorkflowIntervenant()->setIntervenant($intervenant); /* @var $wf \Application\Service\Workflow\WorkflowIntervenant */
        $wf->init();
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'title', 'wf', 'role'));
        
        if ($wf->getCurrentStep()) {
//            var_dump($wf->getStepUrl($wf->getCurrentStep()));
        }
        
        return $view;
    }
    
    public function modifierAction()
    {
        if (!($id = $this->params()->fromRoute('id'))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (!($intervenant = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant '$id' spécifié introuvable.");
        }

        $form = $this->getFormModifier();
        $form->bind($intervenant);

        if (($data = $this->params()->fromPost())) {
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->intervenant()->getEntityManager();
                $em->flush($form->getObject());
            }
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form, 'intervenant' => $intervenant));
        return $view;
    }
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    protected function getIntervenantService()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
    
    private $intervenantsChoisisRecentsSessionContainer;
    
    /**
     * @return \Zend\Session\Container
     */
    protected function getIntervenantsChoisisRecentsSessionContainer()
    {
        if (null === $this->intervenantsChoisisRecentsSessionContainer) {
            $container = new \Zend\Session\Container(get_class() . '_IntervenantsChoisisRecents');
            $container->setExpirationSeconds(2*60*60); // 1 heure
            $this->intervenantsChoisisRecentsSessionContainer = $container;
        }
        return $this->intervenantsChoisisRecentsSessionContainer;
    }
    
    /**
     * 
     * @param bool $clear
     * @return array
     */
    protected function getIntervenantsChoisisRecents($clear = false)
    {
        $container = $this->getIntervenantsChoisisRecentsSessionContainer();
        if ($clear) {
            unset($container->intervenants);
        }
        if (!isset($container->intervenants)) {
            $container->intervenants = array();
        }
        return $container->intervenants;
    }
    
    /**
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return \Application\Controller\IntervenantController
     */
    protected function addIntervenantChoisiRecent(Intervenant $intervenant)
    {
        $container    = $this->getIntervenantsChoisisRecentsSessionContainer();
        $intervenants = (array) $container->intervenants;
        
        if (!array_key_exists($intervenant->getId(), $intervenants)) {
            $intervenants["" . $intervenant] = array(
                'id'         => $intervenant->getId(),
                'sourceCode' => $intervenant->getSourceCode(),
                'nom'        => "" . $intervenant,
            );
            ksort($intervenants);
        }
        $container->intervenants = $intervenants;
        
        return $this;
    }
}
