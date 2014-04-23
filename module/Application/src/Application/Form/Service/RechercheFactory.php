<?php

namespace Application\Form\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Application\Form\Service\Recherche;

/**
 * Description of RechercheFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator) /* @var $serviceLocator \Zend\Mvc\Controller\PluginManager */
    {
        $recherche = new Recherche();
        $recherche->setServiceLocator($serviceLocator);
        $h = $serviceLocator->getServiceLocator()->get('FormServiceRechercheHydrator');
        $recherche->setHydrator($h);

        return $recherche;
    }
}