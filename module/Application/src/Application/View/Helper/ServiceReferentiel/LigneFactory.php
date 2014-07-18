<?php

namespace Application\View\Helper\ServiceReferentiel;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of LigneFactory
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class LigneFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helper = new Ligne();
        $helper->setContextProvider($serviceLocator->getServiceLocator()->get('ApplicationContextProvider'));
        
        return $helper;
    }
}