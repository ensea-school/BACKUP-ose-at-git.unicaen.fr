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
 * @method \Application\Controller\Plugin\Intervenant intervenant()
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
        $qb = $service->finderByContext($context);
        $annee = $context['annee'];
        $services = $qb->getQuery()->execute();
//        return compact('annee', 'services', 'context');
        
        /* Bertrand: services référentiels */
        $controller       = 'Application\Controller\ServiceReferentiel';
        $params           = $this->getEvent()->getRouteMatch()->getParams();
        $params['action'] = 'voirListe';
        $listeViewModel   = $this->forward()->dispatch($controller, $params);
        /* */
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->addChild($listeViewModel, 'servicesRefListe')
                ->setVariables(compact('annee', 'services', 'context'));
        
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
        $id      = (int)$this->params()->fromRoute('id',0);
        $service = $this->getServiceService();
        $context = $service->getGlobalContext();
        $title   = $id ? "Modification" : "Ajout";
        $errors  = array();
        $form = new Saisie( $this->getServiceLocator(), $this->url(), $context );
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if (0 != $id){
            /* Initialisation des valeurs */
            $entity = $service->getRepo()->find($id);
            /* @var $entity \Application\Entity\Db\Service */

            $form->get('id')->setValue( $entity->getId() );

            if (! isset($context['intervenant'])){
                $form->get('intervenant')->setValue(array(
                        'id' => $entity->getIntervenant()->getId(),
                        'label' => (string)$entity->getIntervenant()
                ));
            }
            if ($entity->getElementPedagogique()){
                $form->get('elementPedagogique')->setElementPedagogique($entity->getElementPedagogique());
            }
            $form->get('etablissement')->setValue(array(
                    'id' => $entity->getEtablissement()->getId(),
                    'label' => $entity->getEtablissement()->getLibelle()
            ));
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $post = $request->getPost();
            if (null == $post->etablissement['id']){
                $post->etablissement = array( 'id' => $context['etablissement']->getId(), 'label' => (string)$context['etablissement'] );
            }elseif( (int)$post->etablissement['id'] != $context['etablissement']->getId() ){
                $post->elementPedagogique['element'] = ''; // pas d'élément si un autre établissement a été sélectionné
            }
            $form->setData($post);

            if ($form->isValid()) {
                if (! isset($entity)){
                    $entity = new Service;
                    $entity->setAnnee($context['annee']);
                    $entity->setValiditeDebut(new \DateTime);
                }

                if ( isset($context['intervenant']) && $context['intervenant']){
                    $intervenant = $context['intervenant'];
                }else{
                    $controller = 'Application\Controller\Intervenant';
                    $params     = $this->getEvent()->getRouteMatch()->getParams();
                    if (!($intervenant = $this->intervenant()->getRepo()->findOneBySourceCode($post->intervenant['id']))) {
                        $this->getServiceLocator()->get('importProcessusImport')->intervenant($post->intervenant['id']); // Import
                        if (!($intervenant = $this->intervenant()->getRepo()->findOneBySourceCode($post->intervenant['id']))) {
                            throw new RuntimeException("L'intervenant suivant est introuvable après import : sourceCode = ".$post->intervenant['id'].".");
                        }
                    }
                }

                if (isset($context['elementPedagogique'])){
                    $elementPedagogique = $context['elementPedagogique'];
                }elseif(isset($post->elementPedagogique['element']['id']) && 0 != (int)$post->elementPedagogique['element']['id']){
                    $elementPedagogique = $this->em()->getRepository('Application\\Entity\\Db\\ElementPedagogique')->find($post->elementPedagogique['element']['id']);
                }else{
                    $elementPedagogique = null;
                }

                $etablissement = $this->em()->getRepository('Application\\Entity\\Db\\Etablissement')->find($post->etablissement['id']);


                /* Hydratation */
                if (!$entity->getId() || $entity->getStructureAff() != $intervenant->getStructure() ){
                    $entity->setStructureAff( $intervenant->getStructure() );
                }

                if ($elementPedagogique && (!$entity->getId() || $entity->getStructureEns() != $elementPedagogique->getStructure()) ){
                    $entity->setStructureEns( $elementPedagogique->getStructure() );
                }
                $entity->setIntervenant( $intervenant );
                $entity->setElementPedagogique( $elementPedagogique );
                $entity->setEtablissement( $etablissement );

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

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service/saisie')
                ->setVariables(compact('form', 'context', 'title', 'errors'));
        
        return $viewModel;
    }
}
