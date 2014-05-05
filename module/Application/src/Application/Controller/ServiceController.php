<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\Service\Saisie;
use Application\Entity\Db\Service;
use Application\Exception\DbException;


/**
 * Description of ServiceController
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method \Application\Controller\Plugin\Context context()
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

    public function indexAction()
    {
        $service = $this->getServiceService();
        $context = $service->getGlobalContext();
        $qb = $service->finderByFilterArray($context);
        $annee = $context['annee'];
        if (empty($context['intervenant'])){
            $rechercheForm = $this->getServiceLocator()->get('FormElementManager')->get('ServiceRecherche');
            /* @var $rechercheForm \Application\Form\Service\Recherche */
            $rechercheForm->populateOptions($context);
            $filter = new \StdClass;
            $rechercheForm->bind($filter);
            $rechercheForm->setData( $this->getRequest()->getQuery() );
            if ($rechercheForm->isValid()){
                $service->finderByFilterObject($filter, null, $qb);
            }
            $action = $this->getRequest()->getQuery('action', null); // ne pas afficher par défaut
        }else{
            $rechercheForm = null; // pas de filtrage
            $action = 'afficher'; // Affichage par défaut
        }
        $errors = null;

        $viewModel = new \Zend\View\Model\ViewModel();
        if ('afficher' === $action){
            $services = $service->getList($qb);

            /* Bertrand: services référentiels */
            $controller       = 'Application\Controller\ServiceReferentiel';
            $params           = $this->getEvent()->getRouteMatch()->getParams();
            $params['action'] = 'voirListe';
            $listeViewModel   = $this->forward()->dispatch($controller, $params);
            $viewModel->addChild($listeViewModel, 'servicesRefListe');
            /* */
        }else{
            $services = array();
        }

        $viewModel->setVariables(compact('action', 'annee', 'services', 'rechercheForm', 'context', 'errors'));
        return $viewModel;
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
        $details = 1 == (int)$this->params()->fromQuery('details',0);
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content',0);
        $service = $this->getServiceService();
        $entity  = $service->getRepo()->find($id);
        $context = $service->getGlobalContext();

        return compact('entity', 'context', 'details', 'onlyContent');
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
                $entity->setHistoDestruction(new \DateTime);
                $this->em()->flush();
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
        $context = $service->getGlobalContext();

        $intervenantContext = $context['intervenant'];//$this->context()->intervenantFromContext();

        if ($id){
            $entity = $service->getRepo()->find($id);
            $title   = "Modification de service";
        }else{
            $entity = new Service;
            $entity->setAnnee( $this->context()->anneeFromContext() );
            $entity->setValiditeDebut(new \DateTime );
            $entity->setIntervenant( $intervenantContext );
            $title   = "Ajout de service";
        }
        $form = new Saisie( $this->getServiceLocator(), $this->url(), $context );


        if ($this->getRequest()->isPost()){
            if(! $intervenantContext){
                $entity->setIntervenant( $this->context()->intervenantFromPost("intervenant[id]") );
            }
            $entity->setElementPedagogique( $this->context()->elementPedagogiqueFromPost("elementPedagogique[element][id]") );
            $entity->setEtablissement( $this->context()->etablissementFromPost("etablissement[id]") );
            if (! $entity->getEtablissement()) $entity->setEtablissement( $this->context()->etablissementFromContext() );
        }
        $errors  = array();
        $form->bind( $entity );
        if ($this->getRequest()->isPost()){
            if ($form->isValid()){
                try{
                    $this->em()->persist($entity);
                    $this->em()->flush();
                    $form->get('id')->setValue( $entity->getId() ); // transmet le nouvel ID
                }catch(\Exception $e){
                    $e = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }
        return compact('form', 'context','errors','title');
    }
}
