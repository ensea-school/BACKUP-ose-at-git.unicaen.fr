<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Common\Exception\LogicException;
use Application\Entity\Db\Repository\ElementPedagogiqueRepository;

/**
 * Description of OffreFormationController
 *
 * @method \Doctrine\ORM\EntityManager            em()
 * @method \Application\Controller\Plugin\Context context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class OffreFormationController extends AbstractActionController
{
    /**
     * @var \Zend\Session\Container
     */
    protected $sessionContainer;
    
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
        // filtre structure (obligatoire)
        if (null === $structure) {
            $structure = reset($structuresDistinctes);
        }

        // niveaux distincts pour la structure spécifiée
        $niveauxDistincts = $repoOf->finderDistinctNiveaux(array('structure' => $structure))->getQuery()->getResult();
        
        // etapes distinctes pour la structure spécifiée
        $etapesDistinctes = $repoOf->finderDistinctEtapes(array(
            'structure' => $structure, 
            'niveau' => $niveau))->getQuery()->getResult();
        // filtre étape (facultatif)
        $etape = isset($criteria['etape']) ? $criteria['etape'] : null;
        if (null !== $etape && is_scalar($etape)) {
            $etape = $this->em()->find('Application\Entity\Db\Etape', $etape);
        }

        $ep = new \UnicaenApp\Form\Element\SearchAndSelect('element');
        $ep->setAutocompleteSource($this->url()->fromRoute('of/default', array('action' => 'search-of'), array('query' => $criteria)))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"));
        $form   = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'element-rech'));
        $form->add($ep);
        
        // élément
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
            $criteria['id'] = $element['id'];
        }

        // mise en session des filtres courants (utilisés dans la recherche d'élément pédagogique)
        $session = $this->getSessionContainer();
        $session->structure = $structure->getId();
        $session->niveau    = $niveau;
        $session->etape     = $etape ? $etape->getId() : null;

        // fetch
//        $em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
        $qb = $serviceOf->getRepoElementPedagogique()->finder(array(
            'structure' => $structure, 
            'niveau' => $niveau, 
            'etape' => $etape));
        $entities = $qb->getQuery()->getResult();
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(array(
            'entities'        => $entities,
            'structures'      => $structuresDistinctes,
            'niveaux'         => $niveauxDistincts,
            'etapes'          => $etapesDistinctes,
            'structure'       => $structure->getId(),
            'niveau'          => $niveau,
            'etape'           => $etape ? $etape->getId() : null,
            'form'            => $form,
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
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setTerminal($this->getRequest()->isXmlHttpRequest())
                ->setVariables(compact('element'));

        return $viewModel;
    }

    public function searchOfAction()
    {
        if (!($term = $this->params()->fromQuery('term'))) {
            exit;
        }
        
        // respect des filtres éventuels spécifiés en GET ou sinon en session
        $params['structure'] = $this->context()->structureFromQuery();
        $params['niveau']    = $this->context()->niveauFromQuery();
        $params['etape']     = $this->context()->etapeFromQuery();

        $serviceOf = $this->getServiceLocator()->get('applicationOffreFormation'); /* @var $serviceOf OffreFormationService */
        $repoOf = $serviceOf->getRepoElementPedagogique(); /* @var $serviceOf ElementPedagogiqueRepository */
        
        $params = array();
        $params['term']  = $term;
        $params['limit'] = 51;
        
        // fetch
        $found = $repoOf->finderByTerm($params);

        $result = array();
        foreach ($found as $item) {
            $extra = '';
            $extra .= sprintf('<span class="niveau" title="%s">%s</span> > ', "Niveau", $item['LIBELLE_GTF'] . $item['NIVEAU']);
            $extra .= sprintf('<span class="etape" title="%s">%s</span> > ', "Étape", $item['LIBELLE_ETAPE']);
            $extra .= sprintf('<span class="periode" title="%s">%s</span> >', "Période", $item['LIBELLE_PE']);
            $template = sprintf('<span class="extra">{extra}</span> <span class="element" title="%s">{label}</span>', "Élément pédagogique");
            $result[$item['ID']] = array(
                'id'       => $item['ID'],
                'label'    => $item['SOURCE_CODE'] . ' ' . $item['LIBELLE'],
                'extra'    => $extra,
                'template' => $template,
            );
        };

        $result = \UnicaenApp\Form\Element\SearchAndSelect::truncatedResult($result, 50);
        
        return new \Zend\View\Model\JsonModel($result);
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
