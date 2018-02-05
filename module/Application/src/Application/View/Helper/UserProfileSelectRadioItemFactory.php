<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use UnicaenApp\View\Helper\UserProfileSelectFactory;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileSelectRadioItemFactory extends UserProfileSelectFactory
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $helperPluginManager
     * @return UserProfile
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $container = $helperPluginManager->getServiceLocator();
        $userContextService = $container->get('AuthUserContext');

        $service = new UserProfileSelectRadioItem($userContextService);
        $service
                ->setServiceStructure($this->getServiceStructure())
                ->setStructure($this->getServiceContext()->getStructure());

        return $service;
    }
}