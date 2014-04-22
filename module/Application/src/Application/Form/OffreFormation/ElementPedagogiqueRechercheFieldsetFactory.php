<?php

namespace Application\Form\OffreFormation;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueRechercheFieldsetFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var $serviceLocator \Zend\Form\FormElementManager */
        $url = $serviceLocator->getServiceLocator()->get('viewhelpermanager')->get('url');
        
        $queryTemplate = array('structure' => '__structure__', 'niveau' => '__niveau__', 'etape' => '__etape__');
        $urlStructures = $url('of/default', array('action' => 'search-structures'), array('query' => $queryTemplate));
        $urlNiveaux    = $url('of/default', array('action' => 'search-niveaux'), array('query' => $queryTemplate));
        $urlEtapes     = $url('of/default', array('action' => 'search-etapes'), array('query' => $queryTemplate));
        $urlElements   = $url('of/default', array('action' => 'search-element'), array('query' => $queryTemplate));
        
        $fs = new ElementPedagogiqueRechercheFieldset();
        $fs
                ->setStructuresSourceUrl($urlStructures)
                ->setNiveauxSourceUrl($urlNiveaux)
                ->setEtapesSourceUrl($urlEtapes)
                ->setElementsSourceUrl($urlElements);
        
        $h = $serviceLocator->getServiceLocator()->get('FormElementPedagogiqueRechercheHydrator');
        $fs->setHydrator($h);
        
        return $fs;
    }
}