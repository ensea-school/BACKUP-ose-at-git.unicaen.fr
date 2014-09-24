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
        $this->em()->getFilters()->enable('historique');
        
        $serviceEp    = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceEp ElementPedagogiqueService */
        $serviceEtape = $this->getServiceLocator()->get('applicationEtape'); /* @var $serviceEtape EtapeService */
        $serviceStructure = $this->getServiceLocator()->get('applicationStructure'); /* @var $serviceStructure \Application\Service\Structure */
        $localContext = $this->getContextProvider()->getLocalContext();
        $role         = $this->getContextProvider()->getSelectedIdentityRole();

        // extraction des filtres spécifiés dans la requête
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        if ($niveau) $niveau = $this->getServiceNiveauEtape()->get($niveau); // entité Niveau
        // structure de responsabilité si aucun filtre spécifié
        if (!$structure && $role instanceof \Application\Acl\ComposanteRole) {
            $structure = $role->getStructure();
        }

        // persiste les filtres dans le contexte local
        $localContext
                ->setStructure($structure)
                ->setNiveau($niveau)
                ->setEtape($etape);

        // liste des structures distinctes
        $structuresDistinctes = $serviceStructure->getList( $serviceStructure->finderByEnseignement() );
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
        $qb = $serviceEtape->finderByOrphelines();
        if ($niveau) $serviceEtape->finderByNiveau($niveau, $qb );
        if ($structure) $serviceEtape->finderByStructure($structure, $qb);
        $etapesOrphelines = $structure ? 
                $serviceEtape->getList( $qb ) :
                array();

        $params = [];
        if ($structure) $params['structure'] = $structure->getId();
        if ($niveau) $params['niveau'] = $niveau->getId();
        if ($etape) $params['etape'] = $etape->getId();
        $ep = new \UnicaenApp\Form\Element\SearchAndSelect('element');
        $ep
                ->setAutocompleteSource($this->url()->fromRoute('of/element/default', array('action' => 'search'), ['query' => $params]))
                ->setLabel("Recherche :")
                ->setAttributes(array('title' => "Saisissez 2 lettres au moins"));
        $form = new \Zend\Form\Form('search');
        $form->setAttributes(array('class' => 'element-rech'));
        $form->add($ep);
        
        // élément pédagogique sélectionné dans le champ de recherche
        if (($element = $this->params()->fromPost('element')) && isset($element['id'])) {
            $form->get('element')->setValue($element);
        }

        // fetch
        $entities = null;
        if ($structure) {
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
            'serviceEtape'     => $this->getServiceEtape(), // pour déterminer les droits
            'serviceElement'   => $this->getServiceElementPedagogique(), // pour déterminer les droits
        ));

        return $viewModel;
    }

    /**
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function exportAction()
    {
        $em           = $this->em(); /* @var $em \Doctrine\ORM\EntityManager */
        $serviceEp    = $this->getServiceLocator()->get('applicationElementPedagogique'); /* @var $serviceEp ElementPedagogiqueService */
        $role         = $this->getContextProvider()->getSelectedIdentityRole();

        $em->getFilters()->enable('historique');

        // extraction des filtres spécifiés dans la requête
        $structure = $this->context()->structureFromQuery();
        $niveau    = $this->context()->niveauFromQuery();
        $etape     = $this->context()->etapeFromQuery();
        if ($niveau) $niveau = \Application\Entity\NiveauEtape::getInstance($niveau); // entité Niveau
        // structure de responsabilité si aucun filtre spécifié
        if (!$structure && $role instanceof \Application\Acl\ComposanteRole) {
            $structure = $role->getStructure();
        }

        // fetch
        $entities = null;
        if ($structure) {
            $qb = $serviceEp->finder(array(
                'structure' => $structure,
                'niveau' => $niveau,
                'etape' => $etape));
            $entities = $qb->getQuery()->getResult();
        }

        $csvModel = new \UnicaenApp\View\Model\CsvModel();
        $csvModel->setHeader([
            'Code formation',
            'Libellé formation',
            'Niveau',
            'Code enseignement',
            'Libellé enseignement',
            'Période',
            'FOAD',
            'Régimes d\'inscription'
        ]);
        foreach( $entities as $entity ){ /* @var $entity \Application\Entity\Db\ElementPedagogique */
            $etape = $entity->getEtape();
            $csvModel->addLine([
                $etape->getSourceCode(),
                $etape->getLibelle(),
                $etape->getNiveauToString(),
                $entity->getSourceCode(),
                $entity->getLibelle(),
                $entity->getPeriode(),
                $entity->getTauxFoad(),
                $entity->getRegimesInscription()
            ]);
        }
        $csvModel->setFilename('offre-de-formation.csv');
        return $csvModel;
    }

    /**
     * Retourne au format JSON les structures distinctes des éléments pédagogiques.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function searchStructuresAction()
    {
        $serviceStructure = $this->getServiceLocator()->get('applicationStructure'); /* @var $serviceStructure \Application\Service\Structure */
        $result = $serviceStructure->getList( $serviceStructure->finderByEnseignement() );
        
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
     * @return \Application\Service\NiveauEtape
     */
    protected function getServiceNiveauEtape()
    {
        return $this->getServiceLocator()->get('applicationNiveauEtape');
    }
}
