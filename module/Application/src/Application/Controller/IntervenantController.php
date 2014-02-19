<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Zend\Form\Annotation\AnnotationBuilder;

/**
 * Description of IntervenantController
 *
 * @method \Application\Controller\Plugin\Intervenant intervenant() Description
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantController extends AbstractActionController
{
    public function indexAction()
    {
//        var_dump($this->identity());
//        $em = $this->intervenant()->getEntityManager();
//        $e = new \Application\Entity\Db\Etablissement();
//        $e
//                ->setLibelle('Établissement de test')
//                ->setSource($em->find('Application\Entity\Db\Source', 'Harpege'))
//                ->setSourceCode(rand(1, 999))
////                ->setHistoCreateur($user = $em->find('Application\Entity\Db\Utilisateur', 2))
////                ->setHistoModificateur($user)
//                ;
//        $em->persist($e);
//        $em->flush();
        
        $view = new \Zend\View\Model\ViewModel();
//        $view->setVariables(array('form' => $form, 'intervenant' => $intervenant));
        $this->getEvent()->setParam('modal', true);
        return $view;
    }
    
    public function rechercherAction()
    {
        if (($data = $this->prg()) instanceof \Zend\Http\Response) {
            return $data;
        }
        
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($this->url()->fromRoute('application/default', array('controller' => 'intervenant', 'action' => 'search')))
                ->setLabel("Intervenant :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'intervenant-rech'));
        $form->add($interv);
        
        $intervenant = null;
        
        // post
        if (is_array($data)) {
            $form->setData($data);
//            var_dump($data);
            if ($form->isValid()) {
                $repo = $this->intervenant()->getRepo();
                $intervenant = $repo->findOneBy(array('sourceCode' => $form->get('interv')->getValueId()));
            }
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form, 'intervenant' => $intervenant));
        $view->setTemplate('application/intervenant/rechercher');
        
        return $view;
    }
    
    public function searchAction()
    {
//        if (!$this->getRequest()->isXmlHttpRequest()) {
//            return $this->redirect()->toRoute('home');
//        }
        if (!($term = $this->params()->fromQuery('term'))) {
            exit;
        }
           
        $repo = $this->intervenant()->getRepo();
        $entities = $repo->findByNomPrenomId($term);
        
        $result = array();
        foreach ($entities as $item) { /* @var $item \Application\Entity\Db\Intervenant */
            $result[$item->getSourceCode()] = array(
                'id'    => $item->getSourceCode(),
                'label' => $item->__toString(),
                'extra' => sprintf('%s (%s)', $item->getSourceCode(), $item->getDateNaissance()->format('d/m/Y')),
            );
        };
        
        $service = $this->getServiceLocator()->get('importServiceIntervenant'); /* @var $service \Import\Model\Service\Intervenant */
        $resultHarp = $service->search($term);
        
        
        // marquage des individus existant dans OSE mais inexistant dans la source
        // + retrait des individus trouvés à la fois dans OSE et dans la source
        foreach ($result as $key => $value) {
            if (!array_key_exists($key, $resultHarp)) {
                $result[$key]['extra'] .= ' <i title="Existe dans OSE mais pas dans Harpege"> Introuvable dans Harpege</i>';
            }
            else {
                unset($resultHarp[$key]);
            }
        }
        // marquage des individus inexistant dans OSE mais existant dans la source
        foreach ($resultHarp as $key => $value) {
            if (!array_key_exists($key, $result)) {
                $resultHarp[$key]['extra'] .= ' <i title="Existe dans Harpege mais pas dans OSE"> À importer</i>';
            }
        }
        // union
        $result = $result + $resultHarp;
        
        uasort($result, function($v1, $v2) { return strcasecmp($v1['label'], $v2['label']); });

//        var_dump($result);
        
        return new \Zend\View\Model\JsonModel($result);
    }
    
    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new RuntimeException("Aucun intervenant spécifié.");
        }
        
        $intervenant = $this->intervenant()->getRepo()->find($id);
        if (!$intervenant) {
            $service = $this->getServiceLocator()->get('importServiceIntervenant'); /* @var $service \Import\Model\Service\Intervenant */
            $intervenant = $service->get($id);
        }
        if (!$intervenant) {
            throw new RuntimeException("Intervenant spécifié introuvable.");
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant));
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        return $view;
    }
    
    public function modifierAction()
    {      
        if (!($id = $this->params()->fromRoute('id'))) {
            throw new RuntimeException("Aucun intervenant spécifié.");
        }
        if (!($i = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant spécifié introuvable.");
        }

        $form = $this->getFormModifier();
        $form->bind($i);

        if (($data = $this->params()->fromPost())) {
            $form->setData($data);
            if ($form->isValid()) {
                $em = $this->intervenant()->getEntityManager();
                $em->flush($form->getObject());
            }
        }

        return array('form' => $form);
    }
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
}