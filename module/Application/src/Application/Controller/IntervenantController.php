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
    
    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new RuntimeException("Aucun intervenant spécifié.");
        }
        
        $intervenant = $this->intervenant()->getRepo()->find($id);
        // recherche dans la source externe si introuvable dans OSE
        if (!$intervenant) {
            $service = $this->getServiceLocator()->get('importServiceIntervenant'); /* @var $service \Import\Service\Intervenant */
            $intervenant = $service->get($id); /* @var $intervenant \Import\Model\Entity\Intervenant\Intervenant */
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