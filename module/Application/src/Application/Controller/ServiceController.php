<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\Service\Saisie;
use Application\Entity\Db\Service;


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

    public function indexAction()
    {
        $service = $this->getServiceService();
        $context = $service->getGlobalContext();
        $qb = $service->finderByContext($context);
        $annee = $context['annee'];
        $services = $qb->getQuery()->execute();
        return compact('annee', 'services', 'context');
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

    public function suppressionAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $service = $this->getServiceService();
        $entity  = $service->getRepo()->find($id);
        $entity->setHistoDestruction(new \DateTime);
        $this->em()->flush();

        //$url = $this->url()->fromRoute('service/default', array('action' => 'index'));
        //return $this->redirect()->toUrl($url);

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service/suppression')
                ->setVariables(compact('form', 'context','errors'));
        if ($terminal) {
            return $this->modalInnerViewModel($viewModel, "Suppression de service", false);
        }
        return $viewModel;
    }

    public function saisieAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $service = $this->getServiceService();
        $context = $service->getGlobalContext();
        $errors  = array();
        $form = new Saisie( $this->url(), $context );
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if (0 != $id){
            /* Initialisation des valeurs */
            $entity = $service->getRepo()->find($id);
            /* @var $entity \Application\Entity\Db\Service */
            
            if (! isset($context['intervenant'])){
                $form->get('intervenant')->setValue(array(
                        'id' => $entity->getIntervenant()->getId(),
                        'label' => (string)$entity->getIntervenant()
                ));
            }
            if ($entity->getElementPedagogique()){
                $form->get('elementPedagogique')->setValue(array(
                        'id' => $entity->getElementPedagogique()->getId(),
                        'label' => $entity->getElementPedagogique()->getLibelle()
                ));
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
                $post->elementPedagogique = ''; // pas d'élément si un autre établissement a été sélectionné
            }
            $form->setData($post);

            if ($form->isValid()) {
                if (! isset($entity)){
                    $entity = new Service;
                    $entity->setAnnee($context['annee']);
                    $entity->setValiditeDebut(new \DateTime);
                }
              
                if ( isset($context['intervenant']) ){
                    $intervenant = $context['intervenant'];
                }else{
                    $intervenant = $this->em()->getRepository('Application\\Entity\\Db\\Intervenant')->find($post->intervenant['id']);
                }

                if (isset($context['elementPedagogique'])){
                    $elementPedagogique = $context['elementPedagogique'];
                }elseif(isset($post->elementPedagogique['id']) && 0 != (int)$post->elementPedagogique['id']){
                    $elementPedagogique = $this->em()->getRepository('Application\\Entity\\Db\\ElementPedagogique')->find($post->elementPedagogique['id']);
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

                $this->em()->persist($entity);
                $this->em()->flush();
            }else{
                $errors[] = 'La validation du formulaire a échoué. L\'enregistrement des données n\'a donc pas été fait.';
            }
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service/saisie')
                ->setVariables(compact('form', 'context','errors'));
        if ($terminal) {
            return $this->modalInnerViewModel($viewModel, ((0 != $id) ? "Modification" : "Ajout")." de service", false);
        }
        return $viewModel;
    }
}