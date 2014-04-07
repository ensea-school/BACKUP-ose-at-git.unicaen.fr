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
            throw new RuntimeException("L'intervenant suivant est introuvable après import : sourceCode = $sourceCode.");
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
        
        // fetch intervenant pour simple test d'existence et de type
        $intervenant = $this->em()->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($sourceCode);
        
        // import éventuel
        if (($import = $this->params()->fromQuery('import')) && !$intervenant) {
            $viewModel   = $this->importerAction(); /* @var $viewModel \Zend\View\Model\ViewModel */
            $intervenant = $viewModel->getVariable('intervenant');
        }
        
        // verif type d'intervenant
        if (!$intervenant instanceof \Application\Entity\Db\IntervenantPermanent) {
            throw new RuntimeException("Impossible de saisir un service référentiel pour un intervenant autre que permanent. " .
            "Intervenant spécifié : $intervenant (id = {$intervenant->getId()}).");
        }
        
        // fetch avec jointures
        $qb = $this->em()->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder('ip');
        $qb
                ->leftJoin('ip.serviceReferentiel', 'sr')
                ->leftJoin('sr.fonction', 'fr')
                ->where('ip.sourceCode = :code')
                ->orderBy('sr.id')
                ->setParameter('code', $sourceCode); /* @var $intervenant IntervenantPermanent */
        $intervenant = $qb->getQuery()->getOneOrNullResult();
        
        $this->em()->getFilters()->enable("historique");
        
        $repoFonctionReferentiel = $this->em()->getRepository('Application\Entity\Db\FonctionReferentiel'); /* @var $repoFonctionReferentiel \Doctrine\ORM\EntityRepository */
        $repoElementPedagogique  = $this->em()->getRepository('Application\Entity\Db\ElementPedagogique'); /* @var $repoElementPedagogique \Application\Entity\Db\Repository\ElementPedagogiqueRepository */

        $annee = $this->em()->getRepository('Application\Entity\Db\Annee')->find(2013);

        $structures = $repoElementPedagogique->finderDistinctStructures(array('niveau' => 2))->getQuery()->getResult();
        $fonctions  = $repoFonctionReferentiel->findBy(array('validiteFin' => null), array('libelleCourt' => 'asc'));
        FonctionServiceReferentielFieldset::setStructuresPossibles(new ArrayCollection($structures));
        FonctionServiceReferentielFieldset::setFonctionsPossibles(new ArrayCollection($fonctions));
        
        // NB: patch pour permettre de vider tous les services
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost()->toArray();
            if (empty($data['intervenant']['serviceReferentiel'])) {
                foreach ($intervenant->getServiceReferentiel($annee) as $sr) {
                    $sr->setHistoDestruction(new \DateTime());
                    $this->em()->persist($sr);
                    $this->em()->flush();
                }
                $this->em()->refresh($intervenant);
            }
        }
        
        $form = new \Application\Form\ServiceReferentiel\AjouterModifier();
        $form->getBaseFieldset()->getHydrator()->setAnnee($annee);
        $form->bind($intervenant);
        
        $variables = array(
            'form' => $form, 
            'intervenant' => $intervenant,
        );
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (empty($data['intervenant']['serviceReferentiel'])) {
                $data['intervenant']['serviceReferentiel'] = array();
            }
            var_dump($data);
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->em()->flush();
                    $this->flashMessenger()->addSuccessMessage(sprintf("Service(s) référentiel(s) de $intervenant enregistré(s) avec succès."));
                    $this->redirect()->toRoute('intervenant/default', array('action' => 'voir', 'id' => $intervenant->getId()));
                }
                catch (\Doctrine\DBAL\DBALException $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les services référentiels.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
//                    var_dump($exc->getMessage(), $exc->getTraceAsString());
                }
//                $data = isset($data['intervenant']['serviceReferentiel']) ? $data['intervenant']['serviceReferentiel'] : array();
//                $repo = $this->em()->getRepository('Application\Entity\Db\ServiceReferentiel'); /* @var $repo ServiceReferentielRepository */
//                $repo->updateServicesReferentiel($intervenant, $annee, $data);
            }
        }
        else {
//            $form->bind($intervenant);
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