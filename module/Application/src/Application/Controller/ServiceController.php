<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\Service\Saisie;
use Application\Exception\DbException;


/**
 * Description of ServiceController
 *
 * @method \Doctrine\ORM\EntityManager em()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceController extends AbstractActionController
{
    /**
     * @return \Application\Service\Service
     */
    public function getServiceService()
    {
        return $this->getServiceLocator()->get('ApplicationService');
    }

    /**
     * @return \Application\Service\ContextProvider
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }

    public function indexAction()
    {
        $service = $this->getServiceService();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $qb      = $service->finderByContext();
        $viewModel = new \Zend\View\Model\ViewModel();
        $filter    = new \stdClass();

        /* Initialisation, si ce n'est pas un intervenant, du formulaire de recherche */
        if (! $role instanceof \Application\Acl\IntervenantRole){
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut, sauf si demandé explicitement
            $params           = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'filtres';
            $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
            $viewModel->addChild($listeViewModel, 'filtresListe');

            $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
            /* @var $rechercheForm \Application\Form\Service\Recherche */
            $filter = $rechercheForm->hydrateFromSession();
            $service->finderByFilterObject($filter, null, $qb);
        }
        else {
            $action = 'afficher'; // Affichage par défaut
        }

        // sauvegarde des filtres dans le contexte local
        $this->getContextProvider()->getLocalContext()->fromArray(
                (new \Zend\Stdlib\Hydrator\ObjectProperty())->extract($filter)
        );

        /* Préparation et affichage */
        if ('afficher' === $action){
            $services = $service->getList($qb);

            // services référentiels : délégation au contrôleur
            $controller       = 'Application\Controller\ServiceReferentiel';
            $params           = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'voirListe';
            $params['query']  = $this->params()->fromQuery();
            $listeViewModel   = $this->forward()->dispatch($controller, $params);
            $viewModel->addChild($listeViewModel, 'servicesRefListe');
        }
        else {
            $services = array();
        }

        $viewModel->setVariables(compact('annee', 'services', 'action', 'role'));
        return $viewModel;
    }

    public function resumeAction()
    {
        $viewModel = new \Zend\View\Model\ViewModel();
        
        $annee   = $this->getContextProvider()->getGlobalContext()->getAnnee();
        $action = $this->getRequest()->getQuery('action', null);
        $params = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'filtres';
        $listeViewModel   = $this->forward()->dispatch('Application\Controller\Service', $params);
        $viewModel->addChild($listeViewModel, 'filtresListe');
        $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
        /* @var $rechercheForm \Application\Form\Service\Recherche */
        $filter = $rechercheForm->hydrateFromSession();

        $typesIntervention = $this->getServiceLocator()->get('ApplicationTypeIntervention')->getList();

        $data = array();

        $viewModel->setVariables( compact('annee','action','data','typesIntervention') );
        return $viewModel;
    }

    public function filtresAction()
    {
        $role    = $this->getContextProvider()->getSelectedIdentityRole();

        /* Initialisation, si ce n'est pas un intervenant, du formulaire de recherche */
        if (! $role instanceof \Application\Acl\IntervenantRole){
            $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
            /* @var $rechercheForm \Application\Form\Service\Recherche */
            $rechercheForm->populateOptions();
            $rechercheForm->setDataFromSession();
            $rechercheForm->setData( $this->getRequest()->getQuery() );
            if ($rechercheForm->isValid()){
                $rechercheForm->sessionUpdate();
            }
        }
        else {
            $rechercheForm = null; // pas de filtrage
        }
        return compact('rechercheForm', 'role');
    }

    public function voirAction()
    {
        $service = $this->getServiceService();
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de service spécifié.");
        }
        if (!($service = $service->getRepo()->find($id))) {
            throw new RuntimeException("Service '$id' spécifié introuvable.");
        }

        return compact('service');
    }

    public function voirLigneAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $details = 1 == (int)$this->params()->fromQuery('details',(int)$this->params()->fromPost('details',0));
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content',0);
        $service = $this->getServiceService();
        $entity  = $service->getRepo()->find($id);
        return compact('entity', 'details', 'onlyContent');
    }

    public function suppressionAction()
    {
        $id        = (int) $this->params()->fromRoute('id', 0);
        $service   = $this->getServiceService();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression de service";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                $service->delete($entity);
            }
            catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'context', 'title', 'form'));

        return $viewModel;
    }

    public function saisieAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        $service = $this->getServiceService();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        $context = $this->getContextProvider()->getGlobalContext();

        if ($id){
            $entity = $service->getRepo()->find($id);
            $title   = "Modification de service";
        }else{
            $entity = $service->newEntity();
            $entity->setAnnee( $this->context()->anneeFromGlobalContext() );
            $entity->setValiditeDebut(new \DateTime );
            if ($role instanceof \Application\Acl\IntervenantRole){
                $entity->setIntervenant( $context->getIntervenant() );
            }
            $title   = "Ajout de service";
        }
        $form = new Saisie( $this->getServiceLocator(), $this->url() );


        if ($this->getRequest()->isPost()){
            if (! $role instanceof \Application\Acl\IntervenantRole){
                $entity->setIntervenant( $this->context()->intervenantFromPost("intervenant[id]") );
            }
            $entity->setElementPedagogique( $this->context()->elementPedagogiqueFromPost("elementPedagogique[element][id]") );
            $entity->setEtablissement( $this->context()->etablissementFromPost("etablissement[id]") );
            if (! $entity->getEtablissement()) $entity->setEtablissement( $this->context()->etablissementFromGlobalContext() );
        }
        $errors  = array();
        $form->bind( $entity );
        if ($this->getRequest()->isPost()){
            if ($form->isValid()){
                try{
                    $service->save($entity);
                    $form->get('id')->setValue( $entity->getId() ); // transmet le nouvel ID
                }catch(\Exception $e){
                    $e = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        return compact('form', 'role','errors','title');
    }
}
