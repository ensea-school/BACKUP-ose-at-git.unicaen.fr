<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Entity\Db\Dossier;
use Application\Entity\Db\Listener\DossierListener;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Context     context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantController extends AbstractActionController implements \Application\Service\ContextProviderAwareInterface
{
    use \Application\Service\ContextProviderAwareTrait;
    
    public function indexAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $this->getEvent()->getRouteMatch()->setParam('id', $role->getIntervenant()->getId());
            $this->voirAction();
            return $this->forward()->dispatch('IntervenantController', array('action' => 'voir', 'id' => $role->getIntervenant()->getId()));
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array());

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
                if (($redirect = $this->params()->fromQuery('redirect'))) {
                    $redirect = str_replace('__sourceCode__', $form->get('interv')->getValueId(), $redirect);
                    return $this->redirect()->toUrl($redirect);
                }
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/intervenant/choisir')
                ->setVariables(array('form' => $form, 'title' => "Choisir un intervenant"));
        
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
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        return $view;
    }

    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique');
        $page = $this->params()->fromQuery('page', 'fiche');

        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (!($intervenant = $intervenant = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant '$id' spécifié introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $title = "Détails d'un intervenant";
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
        if ('services' == $page){
            $params = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'intervenant';
            $params['intervenant'] = $intervenant->getSourceCode();
            $servicesViewModel = $this->forward()->dispatch('Application\Controller\Service', $params);
            $view->addChild($servicesViewModel, 'services');
        }
        $view->setVariables(compact('intervenant', 'changements', 'title', 'short', 'page'));
        return $view;
    }

    public function apercevoirAction()
    {
        $this->em()->getFilters()->enable('historique');

        $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);
        $title = "Aperçu d'un intervenant";
        $short = $this->params()->fromQuery('short', false);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(compact('intervenant', 'changements', 'title', 'short'));
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());

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
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        return $view;
    }
    
    public function saisirDossierAction()
    {
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        $form = $this->getFormDossier();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            $intervenant = $this->context()->mandatory()->intervenantFromRoute('id');
        }
        
        if (!$intervenant instanceof \Application\Entity\Db\IntervenantExterieur) {
            throw new RuntimeException("La saisie de dossier n'est possible pour un intervenant extérieur.");
        }
        
        if (!($dossier = $intervenant->getDossier())) {
            $dossier = $this->getDossierService()->newEntity()->fromIntervenant($intervenant);
            $intervenant->setDossier($dossier);
        }
        
        $form->bind($dossier);
        
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDossierService()->save($dossier);
                $this->notifyDossier($dossier);
                $this->getIntervenantService()->save($intervenant);
                $this->flashMessenger()->addSuccessMessage("Dossier enregistré avec succès.");
//                return $this->redirect()->toUrl($this->url()->fromRoute('intervenant'));
            }
        }
        
        return compact('intervenant', 'form');
    }
    
    protected function notifyDossier(Dossier $dossier)
    {
        if (DossierListener::$created || DossierListener::$modified) {
            // envoyer un mail au gestionnaire
            var_dump('Envoi de mail "dossier créé ou modifié"...');
        }
    }
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
    
    /**
     * @return \Application\Form\Intervenant\Dossier
     */
    protected function getFormDossier()
    {
        return $this->getServiceLocator()->get('FormElementManager')->get('IntervenantDossier');
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    protected function getIntervenantService()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }
    
    /**
     * @return \Application\Service\Dossier
     */
    protected function getDossierService()
    {
        return $this->getServiceLocator()->get('ApplicationDossier');
    }
}