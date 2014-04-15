<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Form\Service\Saisie;
use Application\Entity\Db\ServiceReferentiel;
use Application\Exception\DbException;
use Application\Entity\Db\IntervenantPermanent;
use Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset;

/**
 * Description of ServiceReferentielController
 *
 * @method \Doctrine\ORM\EntityManager em()
 * @method \Application\Controller\Plugin\Context context()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ServiceReferentielController extends AbstractActionController
{
    /**
     * @return \Application\Service\Service
     */
    public function getServiceReferentielService()
    {
        return $this->getServiceLocator()->get('ApplicationServiceReferentiel');
    }

    public function indexAction()
    {
        $service  = $this->getServiceReferentielService();
        $context  = $this->context()->getGlobalContext();
        $qb       = $service->finderByContext($context);
        $annee    = $context->getAnnee();
        $services = $qb->getQuery()->execute();
        
        $listeViewModel = new \Zend\View\Model\ViewModel();
        $listeViewModel
                ->setTemplate('application/service-referentiel/voir-liste')
                ->setVariables(compact('services', 'context'));
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setVariables(compact('annee'))
                ->addChild($listeViewModel, 'serviceListe');
        
        return $viewModel;
    }

    public function voirAction()
    {
        $service = $this->getServiceReferentielService();
        if (!($id = $this->params()->fromRoute('id', $this->params()->fromPost('id')))) {
            throw new LogicException("Aucun identifiant de service spécifié.");
        }
        if (!($service = $service->getRepo()->find($id))) {
            throw new RuntimeException("Service '$id' spécifié introuvable.");
        }

        return compact('service');
    }

    public function voirListeAction()
    {
        $service  = $this->getServiceReferentielService();
        $context  = $this->context()->getGlobalContext();
        $qb       = $service->finderByContext($context);
        $annee    = $context->getAnnee();
        $services = $qb->getQuery()->execute();
        
        return compact('annee', 'services', 'context');
    }

    public function voirLigneAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $details = 1 == (int)$this->params()->fromQuery('details',0);
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content',0);
        $service = $this->getServiceReferentielService();
        $entity  = $service->getRepo()->find($id);
        $context = $service->getGlobalContext();
        $details = false;

        return compact('entity', 'context', 'details', 'onlyContent');
    }

    public function suppressionAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $service = $this->getServiceReferentielService();
        $entity  = $service->getRepo()->find($id);
        $errors  = array();

        try{
            $entity->setHistoDestruction(new \DateTime);
            $this->em()->flush();
        }catch(\Exception $e){
            $e = DbException::translate($e);
            $errors[] = $e->getMessage();
        }

        $terminal = $this->getRequest()->isXmlHttpRequest();
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service/suppression')
                ->setVariables(compact('entity', 'context','errors'));
        if ($terminal) {
            return $this->modalInnerViewModel($viewModel, "Suppression de service", false);
        }
        return $viewModel;
    }

    /**
     * 
     * @param bool $import Importer l'intervenant si besoin ?
     * @return \Application\Entity\Db\Intervenant
     * @throws RuntimeException Intervenant spécifié par son code source introuvable
     */
    protected function getIntervenant($import = true)
    {
        $sourceCode = $this->params()->fromQuery('sourceCode');
        
        if ($sourceCode) {
            // test d'existence de l'intervenant et import éventuel
            $intervenant = $this->em()->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($sourceCode);
            if (!$intervenant) {
                if (!$import) {
                    throw new RuntimeException("Intervenant spécifié introuvable (sourceCode = $sourceCode).");
                }
                // import de l'intervenant
                $viewModel   = $this->importerAction(); /* @var $viewModel \Zend\View\Model\ViewModel */
                $intervenant = $viewModel->getVariable('intervenant');
            }
            
            return $intervenant;
        }
        
        $context = $this->context()->getGlobalContext();
        
        if (!($intervenant = $context->getIntervenant())) {
            throw new RuntimeException("Aucun intervenant spécifié dans le contexte.");
        }
        
        return $intervenant;
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function saisirAction()
    {
        $sourceCode  = $this->params()->fromQuery('sourceCode');
        $import      = $this->params()->fromQuery('import', true);
        $service     = $this->getServiceReferentielService();
        $context     = $this->context()->getGlobalContext();
        $isAjax      = $this->getRequest()->isXmlHttpRequest();
        $intervenant = $this->getIntervenant($import);
        
        // si aucun intervenant spécifié, redirection vers le choix d'un intervenant (action qui redirigera ici une fois l'intervenant choisi)
        if (!$intervenant) {
            if ($isAjax) {
                throw new RuntimeException("Aucun intervenant spécifié.");
            }
            $redirect = $this->url()->fromRoute('intervenant/default', array(), array('query' => array('sourceCode' => '__sourceCode__')), true);
            return $this->redirect()->toRoute(
                    'intervenant/default', array('action' => 'choisir'), array('query' => array('redirect' => $redirect)));
        }
        
        // verif type d'intervenant
        if (!$intervenant instanceof IntervenantPermanent) {
            $message = "La saisie de service référentiel n'est possible que pour les intervenants permanents.";
            if ($isAjax) {
                throw new MessageException($message);
            }
            $this->flashMessenger()->addErrorMessage($message);
            $redirect = $this->url()->fromRoute('intervenant/default', array(), array('query' => array('sourceCode' => '__sourceCode__')), true);
            return $this->redirect()->toRoute('intervenant/default', 
                    array('action' => 'choisir'), 
                    array('query' => array('intervenant' => $intervenant->getId(), 'redirect' => $redirect)));
        }
        
        // fetch avec jointures
        $qb = $this->em()->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder('ip');
        $qb
                ->leftJoin('ip.serviceReferentiel', 'sr')
                ->leftJoin('sr.fonction', 'fr')
                ->where('ip.id = :id')
                ->orderBy('sr.id')
                ->setParameter('id', $intervenant->getId()); /* @var $intervenant IntervenantPermanent */
        $intervenant = $qb->getQuery()->getOneOrNullResult();
        
        $this->em()->getFilters()->enable("historique");
        
        $repoFonctionReferentiel = $this->em()->getRepository('Application\Entity\Db\FonctionReferentiel'); /* @var $repoFonctionReferentiel \Doctrine\ORM\EntityRepository */
        $repoElementPedagogique  = $this->em()->getRepository('Application\Entity\Db\ElementPedagogique'); /* @var $repoElementPedagogique \Application\Entity\Db\Repository\ElementPedagogiqueRepository */

        $annee = $context->getAnnee();

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
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));
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
//            var_dump($data);
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->em()->flush();
                    if ($isAjax) {
                        exit;
                    }
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

        $variables['context'] = $context;
                
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service-referentiel/saisir')
                ->setVariables($variables);
        if ($isAjax) {
            return $this->modalInnerViewModel($viewModel, "Saisie du service référentiel", false);
        }
        
        return $viewModel;
    }
}
