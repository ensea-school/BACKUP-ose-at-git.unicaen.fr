<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Doctrine\Common\Collections\ArrayCollection;
use Common\Exception\MessageException;
use Common\Exception\RuntimeException;
use Common\Exception\LogicException;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Exception\DbException;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\IntervenantPermanent;
use Application\Form\ServiceReferentiel\FonctionServiceReferentielFieldset;
use Application\Acl\ComposanteRole;

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

    protected function initFilters()
    {
        $this->em()->getFilters()->enable('historique')
                ->disableForEntity('Application\Entity\Db\FonctionReferentiel');
    }

    public function voirAction()
    {
        $this->em()->getFilters()->enable('historique');
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
        $this->initFilters();
        $role           = $this->getContextProvider()->getSelectedIdentityRole();
        $service        = $this->getServiceServiceReferentiel();
        $intervenant    = $this->context()->intervenantFromRoute();
        if (! $intervenant) $intervenant = $this->context()->intervenantFromQuery('intervenant-filter');
        $filter         = $this->params('filter');
        $qb = $service->finderByContext();
        if ($intervenant) {
            $service->finderByIntervenant( $intervenant, $qb );
            $renderIntervenants = false;
        }else{
            $renderIntervenants = true;
            if ($role instanceof \Application\Acl\ComposanteRole){
                $service->finderByComposante($role->getStructure(), $qb);
            }
        }

        if (isset($filter->structureEns)){
            $service->finderByStructure( $filter->structureEns, $qb );
        }
        if (isset($filter->intervenant)){
            $service->finderByIntervenant( $filter->intervenant, $qb );
        }

        $services = $service->getList( $qb );
        return compact('services', 'intervenant', 'renderIntervenants');
    }

    public function voirLigneAction()
    {
        $this->initFilters();

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
        $this->initFilters();
        
        $id        = $this->params()->fromRoute('id');
        $entity    = $this->em()->find('Application\Entity\Db\ServiceReferentiel', $id); /* @var $entity ServiceReferentiel */
        $title     = "Suppression de service référentiel";
        $form      = new \Application\Form\Supprimer('suppr');
        $viewModel = new \Zend\View\Model\ViewModel();

        // ACL
        if (! $this->isAllowed($entity, 'delete')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }
        
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
        
        $context = $this->getContextProvider()->getLocalContext();
        $role    = $this->getContextProvider()->getSelectedIdentityRole();
        
        if ($role instanceof \Application\Acl\IntervenantRole) {
            $intervenant = $role->getIntervenant();
        }
        else {
            // récupère l'éventuel intervenant du contexte local
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
     * @see IntervenantController
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
        $role        = $this->getContextProvider()->getSelectedIdentityRole();
        
        // si aucun intervenant spécifié, redirection vers le choix d'un intervenant (action qui redirigera ici une fois l'intervenant choisi)
        if (!$intervenant) {
            return $this->redirectToChoisirIntervenant();
        }
        
        // verification règle métier
        $rule = new \Application\Rule\Intervenant\PeutSaisirReferentielRule($intervenant);
        $rule->setStructure($role instanceof ComposanteRole ? $role->getStructure() : null);
        if (!$rule->execute()) {
            throw new MessageException("La saisie de référentiel n'est pas possible. ", null, new \Exception($rule->getMessage()));
        }
        // ACL
        $assertionEntity = $this->getServiceServiceReferentiel()->newEntity()->setIntervenant($intervenant);
        if (! $this->isAllowed($assertionEntity, 'create') || ! $this->isAllowed($assertionEntity, 'update')) {
            throw new MessageException("Cette opération n'est pas autorisée.");
        }
        
        $this->em()->getFilters()->enable("historique");
        
        // fetch intervenant avec jointures
        $qb = $this->getServiceIntervenant()->getFinderIntervenantPermanentWithServiceReferentiel();
        $qb->setIntervenant($intervenant);
        $intervenant = $qb->getQuery()->getOneOrNullResult(); /* @var $intervenant IntervenantPermanent */
        
        $repoFonctionReferentiel = $this->em()->getRepository('Application\Entity\Db\FonctionReferentiel'); /* @var $repoFonctionReferentiel \Doctrine\ORM\EntityRepository */
        $serviceEp = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceEp ElementPedagogiqueService */
        $serviceStructure = $this->getServiceLocator()->get('applicationStructure'); /* @var $serviceStructure \Application\Service\Structure */
        $annee = $context->getAnnee();

        $univ = $serviceStructure->getRacine();
        
        $structures = $serviceStructure->getList($serviceStructure->finderByEnseignement());
        $structures[$univ->getId()] = $univ;
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
            'form'        => $form,
            'intervenant' => $intervenant,
            'fonctions'   => $fonctions,
            'title'       => "Saisie du service référentiel <small>$intervenant</small>",
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost()->toArray();
            if (empty($data['intervenant']['serviceReferentiel'])) {
                $data['intervenant']['serviceReferentiel'] = array();
            }
            $form->setData($data);
            if ($form->isValid()) {
                try {
                    $this->em()->flush();
                    if ($isAjax) {
                        exit;
                    }
                    $this->flashMessenger()->addSuccessMessage(sprintf("Service(s) référentiel(s) de $intervenant enregistré(s) avec succès."));
                    $this->redirect()->toRoute('intervenant/default', array('action' => 'voir', 'intervenant' => $intervenant->getId()));
                }
                catch (\Doctrine\DBAL\DBALException $exc) {
                    $exception = new RuntimeException("Impossible d'enregistrer les services référentiels.", null, $exc->getPrevious());
                    $variables['exception'] = $exception;
                }
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
