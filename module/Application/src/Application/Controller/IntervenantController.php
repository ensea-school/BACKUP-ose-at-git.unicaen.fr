<?php

namespace Application\Controller;

use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of IntervenantController
 *
 * @method \Doctrine\ORM\EntityManager                em()
 * @method \Application\Controller\Plugin\Intervenant intervenant()
 * 
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

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function choisirAction()
    {
        $url    = $this->url()->fromRoute('recherche', array('action' => 'intervenantFind'));
        $interv = new \UnicaenApp\Form\Element\SearchAndSelect('interv');
        $interv->setAutocompleteSource($url)
                ->setRequired(true)
                ->setSelectionRequired(true)
                ->setLabel("Recherchez l'intervenant concerné :")
                ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'intervenant-rech'));
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
        
        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('form' => $form));
        
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
        $this->em()->getFilters()->enable('historique');
        
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant d'intervenant spécifié.");
        }
        if (!($intervenant = $intervenant = $this->intervenant()->getRepo()->find($id))) {
            throw new RuntimeException("Intervenant '$id' spécifié introuvable.");
        }

        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->intervenantGetDifferentiel($intervenant);

        $view = new \Zend\View\Model\ViewModel();
        $view->setVariables(array('intervenant' => $intervenant, 'changements' => $changements));
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
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function saisirServiceReferentielAction()
    {
        // si aucun intervenant spécifié, redirection vers le choix d'un intervenant (action qui redirigera ici une fois l'intervenant choisi)
        if (!($sourceCode = $this->params()->fromQuery('sourceCode'))) {
            $redirect = $this->url()->fromRoute('intervenant/default', array(), array('query' => array('sourceCode' => '__sourceCode__')), true);
            return $this->redirect()->toRoute(
                    'intervenant/default', array('action' => 'choisir'), array('query' => array('redirect' => $redirect)));
        }

        if (($intervenant = $this->intervenant()->getRepo()->find($sourceCode))) {
            throw new RuntimeException("L'intervenant spécifié a déjà été importé : sourceCode = $sourceCode.");
        }
        
        // fetch intervenant
        $intervenant = $this->intervenant()->getRepo()->findOneBy(array('sourceCode' => $sourceCode)); /* @var $intervenant IntervenantPermanent */
        
        // import si demandé et si besoin
        $import = $this->params()->fromQuery('import');
        if ($import && !$intervenant) {
            $viewModel   = $this->importerAction(); /* @var $viewModel \Zend\View\Model\ViewModel */
            $intervenant = $viewModel->getVariable('intervenant');
        }
//        if ($result instanceof \Zend\Http\Response) {
//            return $result;
//        }

        if (!$intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            throw new RuntimeException("Impossible de saisir un service référentiel pour un intervenant autre que permanent. " .
            "Intervenant spécifié : $intervenant (id = {$intervenant->getId()}).");
        }
        
        $this->em()->getFilters()->enable("historique");
        
        $repository = $this->em()->getRepository('Application\Entity\Db\FonctionReferentiel'); /* @var $repository \Doctrine\ORM\EntityRepository */

        $annee = $this->em()->getRepository('Application\Entity\Db\Annee')->find(2013);

        $fonctions = $repository->findBy(array('validiteFin' => null), array('libelleCourt' => 'asc'));
        FonctionServiceReferentielFieldset::setFonctionsPossibles(new ArrayCollection($fonctions));
        
        $form = new \Application\Form\ServiceReferentiel\AjouterModifier();
//        if (!$this->getRequest()->isPost()) {
            $form->bind($intervenant->setAnneeCriterion($annee));
//        }
        
        $variables = array(
            'form' => $form, 
            'intervenant' => $intervenant,
        );
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (!isset($data['intervenant']['serviceReferentiel'])) {
                $data['intervenant']['serviceReferentiel'] = array();
            }
            var_dump($data);
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->em()->flush();
                    $this->flashMessenger()->addSuccessMessage(sprintf("Service(s) référentiel(s) de $intervenant enregistré(s) avec succès."));
//                    $this->redirect()->toRoute('intervenant/default', array('action' => 'voir', 'id' => $intervenant->getId()));
                }
                catch (\Doctrine\DBAL\DBALException $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les services référentiels.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
                }
//                $data = isset($data['intervenant']['serviceReferentiel']) ? $data['intervenant']['serviceReferentiel'] : array();
//                $repo = $this->em()->getRepository('Application\Entity\Db\ServiceReferentiel'); /* @var $repo ServiceReferentielRepository */
//                $repo->updateServicesReferentiel($intervenant, $annee, $data);
            }
        }
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables($variables);

        return $viewModel;
    }
    
    protected function getFormModifier()
    {
        $builder = new AnnotationBuilder();
        $form    = $builder->createForm('Application\Entity\Db\Intervenant');
        $form->getHydrator()->setUnderscoreSeparatedKeys(false);
        
        return $form;
    }
}