<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\LogicException;
use Application\Entity\Db\Repository\ElementPedagogiqueRepository;
use Application\Service\OffreFormation as OffreFormationService;
use Application\Service\Etape as EtapeService;
use Application\Exception\DbException;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of OffreFormationController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class OffreFormationController extends AbstractActionController implements ContextProviderAwareInterface
{
    use ContextProviderAwareTrait;

    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;


    /**
     * @return \Application\Service\ServiceReferentiel
     */
    public function getContextProvider()
    {
        return $this->getServiceLocator()->get('ApplicationContextProvider');
    }

    /**
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $em        = $this->em(); /* @var $em \Doctrine\ORM\EntityManager */
        $serviceOf = $this->getServiceLocator()->get('applicationOffreFormation'); /* @var $serviceOf OffreFormationService */
        $repoOf    = $serviceOf->getRepoElementPedagogique(); /* @var $serviceOf ElementPedagogiqueRepository */
        $criteria  = array_filter($this->params()->fromQuery());
        $em->getFilters()->enable('historique');
//        $em->getFilters()->enable('validite');
        
        // extraction des critères spécifiés dans la requête
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        
        // structures distinctes
        $structuresDistinctes = $repoOf->finderDistinctStructures(array('niveau' => 2))->getQuery()->getResult();
        
        // niveaux distincts pour la structure spécifiée
        $niveauxDistincts = $repoOf->finderDistinctNiveaux(array('structure' => $structure))->getQuery()->getResult();
        
        // etapes distinctes pour la structure spécifiée
        $etapesDistinctes = $repoOf->finderDistinctEtapes(array('structure' => $structure, 'niveau' => $niveau))->getQuery()->getResult();
        // filtre étape (facultatif)
        $etape = isset($criteria['etape']) ? $criteria['etape'] : null;
        if (null !== $etape && is_scalar($etape)) {
            $etape = $this->em()->find('Application\Entity\Db\Etape', $etape);
        }
        
        $ep = new \UnicaenApp\Form\Element\SearchAndSelect('element');
        $ep
                ->setAutocompleteSource($this->url()->fromRoute('of/default', array('action' => 'search-element'), array('query' => $criteria)))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'element-rech'));
        $form->add($ep);
        
        // élément
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
            $criteria['id'] = $element['id'];
        }

        // mise en session des filtres courants (utilisés dans la recherche d'élément pédagogique)
        $session = $this->getSessionContainer();
        $session->structure = $structure ? $structure->getId() : null;
        $session->niveau    = $niveau;
        $session->etape     = $etape ? $etape->getId() : null;

        // fetch
        $entities = null;
        if ($structure) {
            //$em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
            $qb = $serviceOf->getRepoElementPedagogique()->finder(array(
                'structure' => $structure, 
                'niveau' => $niveau, 
                'etape' => $etape));
            $entities = $qb->getQuery()->getResult();
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(array(
            'entities'      => $entities,
            'structures'    => $structuresDistinctes,
            'niveaux'       => $niveauxDistincts,
            'etapes'        => $etapesDistinctes,
            'structure'     => $structure ? $structure->getId() : null,
            'niveau'        => $niveau,
            'etape'         => $etape ? $etape->getId() : null,
            'form'          => $form,
            'serviceEtape'  => $this->getServiceEtape() // pour déterminer les droits
        ));

        return $viewModel;
    }

    public function voirElementAction()
    {
        if (!($id = $this->params()->fromQuery('id'))) {
            throw new LogicException("Aucun élément spécifié.");
        }

        $em      = $this->intervenant()->getEntityManager(); /* @var $em \Doctrine\ORM\EntityManager */
        $repoEp  = $em->getRepository('Application\Entity\Db\ElementPedagogique'); /* @var $repoEp ElementPedagogiqueRepository */
        $element = $repoEp->find($id);
        $title   = "Détails de l'élément pédagogique";
        $short   = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTemplate('application/offre-formation/voir-element')
                ->setVariables(compact('element', 'title', 'short'));
        
        return $viewModel;
    }

    public function apercevoirElementAction()
    {
        if (!($id = $this->params()->fromQuery('id'))) {
            throw new LogicException("Aucun élément spécifié.");
        }

        $em      = $this->intervenant()->getEntityManager(); /* @var $em \Doctrine\ORM\EntityManager */
        $repoEp  = $em->getRepository('Application\Entity\Db\ElementPedagogique'); /* @var $repoEp ElementPedagogiqueRepository */
        $element = $repoEp->find($id);
        $short   = $this->params()->fromQuery('short', false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest())
                ->setVariables(compact('element', 'short'));

        return $viewModel;
    }

    public function searchOfAction()
    {
        throw new \BadMethodCallException("Méthode obsolète, veuillez utiliser searchElementAction().");
    }
    
    public function searchElementAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            exit;
        }
        
        // respect des filtres éventuels spécifiés en GET ou sinon en session
        $params = array();
        $params['structure'] = $this->context()->structureFromQuery();
        $params['niveau']    = $this->context()->niveauFromQuery();
        $params['etape']     = $this->context()->etapeFromQuery();
        $params['term']      = $term;
        $params['limit']     = $limit = 101;
        
        // fetch
        $serviceOf = $this->getServiceLocator()->get('applicationOffreFormation'); /* @var $serviceOf OffreFormationService */
        $repoOf = $serviceOf->getRepoElementPedagogique(); /* @var $serviceOf ElementPedagogiqueRepository */
        $found = $repoOf->finderByTerm($params);

        $result = array();
        foreach ($found as $item) {
            $extra = '';
            $extra .= sprintf('<span class="element-rech niveau" title="%s">%s</span>', "Niveau", $item['LIBELLE_GTF'] . $item['NIVEAU']);
            $extra .= sprintf('<span class="element-rech etape" title="%s">%s</span>', "Étape", $item['LIBELLE_ETAPE']);
            $extra .= "Année" !== $item['LIBELLE_PE'] ? sprintf('<span class="element-rech periode" title="%s">%s</span>', "Période", $item['LIBELLE_PE']) : null;
            $template = sprintf('<span class="element-rech extra">{extra}</span><span class="element-rech element" title="%s">{label}</span>', "Élément pédagogique");
            $result[$item['ID']] = array(
                'id'       => $item['ID'],
                'label'    => $item['SOURCE_CODE'] . ' ' . $item['LIBELLE'],
                'extra'    => $extra,
                'template' => $template,
            );
        };

        $result = \UnicaenApp\Form\Element\SearchAndSelect::truncatedResult($result, $limit - 1);
        
        return new \Zend\View\Model\JsonModel($result);
    }
    
    /**
     * Retourne au format JSON les structures distinctes des éléments pédagogiques.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchStructuresAction()
    {
        $params = array('niveau' => 2);
        
        $result = $this->getServiceOffreFormation()->getRepoElementPedagogique()->finderDistinctStructures($params)->getQuery()->getResult();
        
        return new \Zend\View\Model\JsonModel(\UnicaenApp\Util::collectionAsOptions($result));
    }
    
    /**
     * Retourne au format JSON les niveaux distincts des éléments pédagogiques 
     * pour la structure éventuellement spécifiée en GET.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchNiveauxAction()
    {
        $structure = $this->context()->structureFromQuery();
        
        $params = array();
        $params['structure'] = $structure instanceof \Application\Entity\Db\Structure ? $structure : null;
        
        $result = $this->getServiceOffreFormation()->getRepoElementPedagogique()->finderDistinctNiveaux($params)->getQuery()->getResult();

        $result = array_combine(
                $tmp = array_map(function($v) { return $v['libelleCourt'] . $v['niveau']; }, $result), 
                $tmp); 
                
        return new \Zend\View\Model\JsonModel(\UnicaenApp\Util::collectionAsOptions($result));
    }
    
    /**
     * Retourne au format JSON les étapes distincts des éléments pédagogiques 
     * pour la structure et le niveau éventuellement spécifiés en GET.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchEtapesAction()
    {
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        
        $params = array();
        $params['structure'] = $structure instanceof \Application\Entity\Db\Structure ? $structure : null;
        $params['niveau']    = $niveau;
        
        $result = $this->getServiceOffreFormation()->getRepoElementPedagogique()->finderDistinctEtapes($params)->getQuery()->getResult();
        
        return new \Zend\View\Model\JsonModel(\UnicaenApp\Util::collectionAsOptions($result));
    }

    public function etapeSaisieAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        $errors = array();
        if ($id){
            $entity = $this->getServiceEtape()->getRepo()->find($id);
        }else{
            $entity = $this->getServiceEtape()->newEntity();
        }
        $form = $this->getServiceLocator()->get('FormElementManager')->get('EtapeSaisie');
        /* @var $form \Application\Form\OffreFormation\EtapeSaisie */
        $form->bind($entity);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                try{
                    $this->getServiceEtape()->save($entity);
                    $form->get('id')->setValue( $entity->getId() ); // transmet le nouvel ID
                }catch(\Exception $e){
                    $e = DbException::translate($e);
                    $errors[] = $e->getMessage();
                }
            }
        }

        return compact('form','errors');
    }

    public function etapeSuppressionAction()
    {
        $id        = (int) $this->params()->fromRoute('id', 0);
        if (! $id){
            throw new \Common\Exception\RuntimeException('L\'identifiant n\'est pas bon ou n\'a pas été fourni');
        }
        $service   = $this->getServiceEtape();
        $entity    = $service->getRepo()->find($id);
        $title     = "Suppression d'étape";
        $form      = new \Application\Form\Supprimer('suppr');
        $errors = array();
        $form->setAttribute('action', $this->url()->fromRoute(null, array(), array(), true));

        if ($this->getRequest()->isPost()) {
            try {
                $service->delete($entity);
            }catch(\Exception $e){
                $e = DbException::translate($e);
                $errors[] = $e->getMessage();
            }
        }
        return compact('entity', 'title', 'form', 'errors');
    }

    public function etapeApercevoirAction()
    {
        if (!($id = $this->params()->fromRoute('id'))) {
            throw new LogicException("Aucune étape spécifiée.");
        }

        $em      = $this->em();
        $repoEp  = $em->getRepository('Application\Entity\Db\Etape');
        $etape = $repoEp->find($id);
        $import = $this->getServiceLocator()->get('ImportProcessusImport');
        $changements = $import->etapeGetDifferentiel($etape);
        $short = $this->params()->fromQuery('short', false);

        return compact('etape','short','changements');
    }

    /**
     * Retourne le service OffreFormation.
     * 
     * @return OffreFormationService
     */
    protected function getServiceOffreFormation()
    {
        return $this->getServiceLocator()->get('applicationOffreFormation');
    }

    /**
     * Retourne le service Etape
     *
     * @return EtapeService
     */
    protected function getServiceEtape()
    {
        return $this->getServiceLocator()->get('applicationEtape');
    }

    /**
     * @return \Zend\Session\Container
     */
    protected function getSessionContainer()
    {
        if (null === $this->sessionContainer) {
            $this->sessionContainer = new \Zend\Session\Container(get_class());
        }
        return $this->sessionContainer;
    }
}
