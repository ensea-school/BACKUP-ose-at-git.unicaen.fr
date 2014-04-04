<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * 
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Context     context()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * @method \Application\Controller\Plugin\Structure   structure()
 */
class DemoController extends AbstractActionController
{
    /**
     * 
     * @return type
     */
    public function indexAction()
    {
        $queryTemplate = array('structure' => '__structure__', 'niveau' => '__niveau__', 'etape' => '__etape__');
        $urlStructures = $this->url()->fromRoute('of/default', array('action' => 'search-structures'), array('query' => $queryTemplate));
        $urlNiveaux    = $this->url()->fromRoute('of/default', array('action' => 'search-niveaux'), array('query' => $queryTemplate));
        $urlEtapes     = $this->url()->fromRoute('of/default', array('action' => 'search-etapes'), array('query' => $queryTemplate));
        $urlElements   = $this->url()->fromRoute('of/default', array('action' => 'search-element'), array('query' => $queryTemplate));
        
        $fs = new \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset('fs');
        $fs
                ->setStructuresSourceUrl($urlStructures)
                ->setNiveauxSourceUrl($urlNiveaux)
                ->setEtapesSourceUrl($urlEtapes)
                ->setElementsSourceUrl($urlElements)
                ->setStructureEnabled(false)
//                ->setNiveauEnabled(false)
                ->setEtapeEnabled(false)
        ;
        
        return array('fs' => $fs);
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function intervenantAction()
    {
        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setLabel("Rechercher un intervenant :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $form   = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'intervenant-rech'));
        $form->add($interv);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form));

        return $view;
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     * @see IntervenantController
     */
    public function voirIntervenantAction()
    {
        if (!($sourceCode = $this->params()->fromQuery('sourceCode', $this->params()->fromPost('sourceCode')))) {
            if ($this->getRequest()->isXmlHttpRequest()) {
                exit;
            }
            return $this->redirect()->toRoute('home');
        }

        $controller = 'Application\Controller\Intervenant';
        $params     = $this->getEvent()->getRouteMatch()->getParams();

        // import si besoin
        if (!($intervenant = $this->intervenant()->getRepo()->findOneBy(array('sourceCode' => $sourceCode)))) {
            $params['action'] = 'importer';
            $params['id']     = $sourceCode;
            $viewModel        = $this->forward()->dispatch($controller, $params); /* @var $viewModel \Zend\View\Model\ViewModel */
            $intervenant      = $viewModel->getVariable('intervenant');
        }

        $params['action'] = 'voir';
        $params['id']     = $intervenant->getId();
        $viewModel        = $this->forward()->dispatch($controller, $params);

        return $viewModel;
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function saisirServiceReferentielIntervenantAction()
    {
        $controller = 'Application\Controller\Intervenant';
        $params     = $this->getEvent()->getRouteMatch()->getParams();

        $params['action'] = 'saisirServiceReferentiel';
        $viewModel        = $this->forward()->dispatch($controller, $params);

        return $viewModel;
    }
}