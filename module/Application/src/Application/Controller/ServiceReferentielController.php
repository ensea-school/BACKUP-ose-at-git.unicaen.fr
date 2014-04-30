<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
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
class ServiceReferentielController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;
    
    /**
     * @return \Application\Service\ServiceReferentiel
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }
    
    /**
     * @return \Application\Service\ServiceReferentiel
     */
    public function getServiceServiceReferentiel()
    {
        return $this->getServiceLocator()->get('ApplicationServiceReferentiel');
    }
    
    /**
     * @return \Application\Service\Intervenant
     */
    public function getServiceIntervenant()
    {
        return $this->getServiceLocator()->get('ApplicationIntervenant');
    }

    public function indexAction()
    {
        $service  = $this->getServiceServiceReferentiel();
        $cp       = $this->getContextProvider();
        $annee    = $cp->getGlobalContext()->getAnnee();
        $criteria = array();
//        $criteria = array('structure' => $this->em()->find('Application\Entity\Db\Structure', 8494));
        $services = $service->getFinder($criteria)
                ->orderBy("i.nomUsuel, s.libelleCourt")
                ->getQuery()->execute();
        
        $listeViewModel = new \Zend\View\Model\ViewModel();
        $listeViewModel
                ->setTemplate('application/service-referentiel/voir-liste')
                ->setVariables(compact('services'));
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setVariables(compact('annee'))
                ->addChild($listeViewModel, 'serviceListe');
        
        return $viewModel;
    }

    public function voirAction()
    {
        $service = $this->getServiceServiceReferentiel();
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
        $service  = $this->getServiceServiceReferentiel();
        $criteria = array();
//        $criteria = array('structure' => $this->em()->find('Application\Entity\Db\Structure', 8474));
        $services = $service->getFinder($criteria)
                ->orderBy("i.nomUsuel, s.libelleCourt")
                ->getQuery()->execute();
        
        return compact('services');
    }

    public function voirLigneAction()
    {
        $id      = (int)$this->params()->fromRoute('id',0);
        $details = 1 == (int)$this->params()->fromQuery('details',0);
        $onlyContent = 1 == (int)$this->params()->fromQuery('only-content',0);
        $service = $this->getServiceServiceReferentiel();
        $entity  = $service->getRepo()->find($id);
        $context = $service->getGlobalContext();
        $details = false;

        return compact('entity', 'context', 'details', 'onlyContent');
    }

    public function supprimerAction()
    {
        $id        = $this->params()->fromRoute('id');
        $entity    = $this->em()->find('Application\Entity\Db\ServiceReferentiel', $id);
        $title     = "Suppression de service référentiel";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        $form->setAttribute('action', $this->getRequest()->getRequestUri());

        if ($this->getRequest()->isPost()) {
            $errors = array();
            try {
                $this->getServiceServiceReferentiel()->delete($entity);
            }
            catch (\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
            $viewModel->setVariable('errors', $errors);
        }

        $viewModel->setVariables(compact('entity', 'title', 'form'));

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
        $sourceCode  = $this->params()->fromQuery('sourceCode');
        $intervenant = null;
        
        if ($sourceCode) {
            // test d'existence de l'intervenant et import éventuel
            $intervenant = $this->em()->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($sourceCode);
            if (!$intervenant) {
                if (!$import) {
                    throw new RuntimeException("Intervenant spécifié introuvable (sourceCode = $sourceCode).");
                }
                // import de l'intervenant
                $intervenant = $this->getServiceLocator()->get('ApplicationIntervenant')->importer($sourceCode);
            }
            
            return $intervenant;
        }
        
        $context = $this->getContextProvider()->getGlobalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $context->getIntervenant();
        }
        
        return $intervenant;
    }
    
    /**
     * Redirection vers le choix d'un intervenant (action qui redirigera vers l'action 
     * courante une fois l'intervenant choisi).
     * 
     * @param \Application\Entity\Db\Intervenant $intervenant Intervenant pré-choisi
     * @return \Zend\Http\Response
     */
    protected function redirectToChoisirIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $modal    = $this->params()->fromQuery('modal');
        $redirect = $this->url()->fromRoute(
                null, 
                array(), 
                array('query' => array('sourceCode' => '__sourceCode__', 'modal' => $modal)), 
                true);
        
        if ($intervenant) {
           $intervenant = $intervenant->getSourceCode();
        }
        
        return $this->redirect()->toRoute(
                'intervenant/default', 
                array('action' => 'choisir'), 
                array('query' => array('intervenant' => $intervenant, 'redirect' => $redirect, 'modal' => $modal)));
    }
    
    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function saisirAction()
    {
        $import      = $this->params()->fromQuery('import', true);
        $context     = $this->getContextProvider()->getGlobalContext();
        $isAjax      = $this->getRequest()->isXmlHttpRequest();
        $intervenant = $this->getIntervenant($import);
        
        // si aucun intervenant spécifié, redirection vers le choix d'un intervenant (action qui redirigera ici une fois l'intervenant choisi)
        if (!$intervenant) {
            return $this->redirectToChoisirIntervenant();
        }
        
        // verifications concernant l'intervenant
        try {
            $this->getServiceIntervenant()->checkIntervenantForServiceReferentiel($intervenant);
        }
        catch (\Common\Exception\DomainException $exc) {
            $message = $exc->getMessage();
            $this->flashMessenger()->addErrorMessage($message);
            
            return $this->redirectToChoisirIntervenant($intervenant);
        }
        
        $this->em()->getFilters()->enable("historique");
//        var_dump(get_class($intervenant));
        
        // fetch intervenant avec jointures
        $qb = $this->getServiceIntervenant()->getFinderIntervenantPermanentWithServiceReferentiel();
        $qb->setIntervenant($intervenant);
        $intervenant = $qb->getQuery()->getOneOrNullResult();
//                var_dump($qb->getQuery()->getDQL(), $qb->getQuery()->getParameters());
        
        $repoFonctionReferentiel = $this->em()->getRepository('Application\Entity\Db\FonctionReferentiel'); /* @var $repoFonctionReferentiel \Doctrine\ORM\EntityRepository */
        $repoElementPedagogique  = $this->em()->getRepository('Application\Entity\Db\ElementPedagogique');  /* @var $repoElementPedagogique \Application\Entity\Db\Repository\ElementPedagogiqueRepository */

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
        $form->setAttribute('action', $this->getRequest()->getRequestUri());
        $form->getBaseFieldset()->getHydrator()->setAnnee($annee);
        $form->bind($intervenant);
        
        $variables = array(
            'form' => $form, 
            'intervenant' => $intervenant,
            'title' => "Saisie du service référentiel <small>$intervenant</small>",
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
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables($variables);

        $variables['context'] = $context;
                
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setTemplate('application/service-referentiel/saisir')
                ->setVariables($variables);
        
        return $viewModel;
    }
}
