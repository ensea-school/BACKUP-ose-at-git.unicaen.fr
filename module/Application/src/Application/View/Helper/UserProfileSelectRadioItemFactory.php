<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileSelectRadioItemFactory extends \UnicaenApp\View\Helper\UserProfileSelectFactory
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $helperPluginManager
     * @return UserProfile
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $serviceLocator     = $helperPluginManager->getServiceLocator();
        $userContextService = $serviceLocator->get('AuthUserContext');
        $structureService   = $serviceLocator->get('ApplicationStructure');
        $contextProvider    = $serviceLocator->get('ApplicationContextProvider');

        $service = new UserProfileSelectRadioItem($userContextService);
        $service
                ->setServiceStructure($structureService)
                ->setStructureSelectionnee($contextProvider->getGlobalContext()->getStructure());
        
        return $service;
    }
}