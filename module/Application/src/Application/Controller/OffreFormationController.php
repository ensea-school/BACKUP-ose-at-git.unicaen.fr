<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Application\Service\OffreFormation as OffreFormationService;
use Application\Service\ElementPedagogique as ElementPedagogiqueService;
use Application\Service\Etape as EtapeService;
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
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $em           = $this->em(); /* @var $em \Doctrine\ORM\EntityManager */
        $serviceEp    = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceEp ElementPedagogiqueService */
        $localContext = $this->getContextProvider()->getLocalContext();
        $role         = $this->getContextProvider()->getSelectedIdentityRole();

        $em->getFilters()->enable('historique');
//        $em->getFilters()->enable('validite');
        
        // extraction des filtres spécifiés dans la requête
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        
        // structure de responsabilité si aucun filtre spécifié
        if (!$structure && $role instanceof \Application\Acl\DbRole) {
            $structure = $role->getStructure();
        }
        
        // persiste les filtres dans le contexte local
        $localContext
                ->setStructure($structure)
                ->setNiveau($niveau)
                ->setEtape($etape);
        
        // liste des structures distinctes
        $structuresDistinctes = $serviceEp->finderDistinctStructures(array('niveau' => 2))->getQuery()->getResult();
        // niveaux distincts pour la structure spécifiée
        $niveauxDistincts = $structure ? 
                $serviceEp->finderDistinctNiveaux(array('structure' => $structure))->getQuery()->getResult() :
                array();
        $niveauxDistincts = \Application\Entity\NiveauEtape::getInstancesFromEtapes($niveauxDistincts);
        // liste des etapes distinctes pour la structure et le niveau spécifiés
        $etapesDistinctes = $structure ? 
                $serviceEp->finderDistinctEtapes(array('structure' => $structure, 'niveau' => $niveau))->getQuery()->getResult() :
                array();
        // liste des etapes orphelines (sans ÉP) pour la structure et le niveau spécifiés
        $etapesOrphelines = $structure ? 
                $serviceEp->finderDistinctEtapesOrphelines(array('structure' => $structure, 'niveau' => $niveau))->getQuery()->getResult() :
                array();
        
        $ep = new \UnicaenApp\Form\Element\SearchAndSelect('element');
        $ep
                ->setAutocompleteSource($this->url()->fromRoute('of/element/default', array('action' => 'search')))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'element-rech'));
        $form->add($ep);
        
        // élément pédagogique sélectionné dans le champ de recherche
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
        }

        // mise en session des filtres courants (utilisés dans la recherche d'élément pédagogique)
        $session = $this->getSessionContainer();
        $session->structure = $structure ? $structure->getId() : null;
        $session->niveau    = $niveau;
        $session->etape     = $etape ? $etape->getId() : null;

        // fetch
        $entities = null;
        if ($structure) {
//            $em->getConfiguration()->setSQLLogger(new \Doctrine\DBAL\Logging\EchoSQLLogger());
            $qb = $serviceEp->finder(array(
                'structure' => $structure, 
                'niveau' => $niveau, 
                'etape' => $etape));
            $entities = $qb->getQuery()->getResult();
        }

        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel->setVariables(array(
            'entities'         => $entities,
            'structures'       => $structuresDistinctes,
            'niveaux'          => $niveauxDistincts,
            'etapes'           => $etapesDistinctes,
            'etapesOrphelines' => $etapesOrphelines,
            'structure'        => $structure,
            'niveau'           => $niveau,
            'etape'            => $etape,
            'form'             => $form,
            'serviceEtape'     => $this->getServiceEtape() // pour déterminer les droits
        ));

        return $viewModel;
    }
    
    /**
     * Retourne au format JSON les structures distinctes des éléments pédagogiques.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchStructuresAction()
    {
        $params = array('niveau' => 2);
        
        $result = $this->getServiceElementPedagogique()->finderDistinctStructures($params)->getQuery()->getResult();
        
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
        
        $etapes  = $this->getServiceElementPedagogique()->finderDistinctNiveaux($params)->getQuery()->getResult();
        $niveaux = \Application\Entity\NiveauEtape::getInstancesFromEtapes($etapes);
                
        return new \Zend\View\Model\JsonModel(\UnicaenApp\Util::collectionAsOptions($niveaux));
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
     * Retourne le service ElementPedagogique.
     * 
     * @return ElementPedagogiqueService
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->get('applicationElementPedagogique');
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
