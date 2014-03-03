<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
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
    
    public function importerAction()
    {
        if (!($sourceCode = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (($intervenant = $this->intervenant()->getRepo()->find($sourceCode))) {
            throw new RuntimeException("L'intervenant spécifié a déjà été importé : sourceCode = $sourceCode.");
        }
        
        $import = $this->getServiceLocator()->get('importProcessusImport'); /* @var $import \Import\Processus\Import */
        $import->intervenant($sourceCode);

        if (!($intervenant = $this->intervenant()->getRepo()->findOneBy(array('sourceCode' => $sourceCode)))) {
            throw new RuntimeException("L'intervenant suivant est introuvable malgré son import : sourceCode = $sourceCode.");
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant));
        $view->setTerminal($this->getRequest()->isXmlHttpRequest());
        
        return $view;
    }
    
    public function voirAction()
    {
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (!($intervenant = $intervenant = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant '$id' spécifié introuvable.");
        }
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant));
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
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
}