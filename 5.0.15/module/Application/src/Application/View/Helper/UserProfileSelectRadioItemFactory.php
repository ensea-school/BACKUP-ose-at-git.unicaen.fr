<?php

namespace Application\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;
use UnicaenApp\View\Helper\UserProfileSelectFactory;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\Traits\ContextAwareTrait;
use Application\Service\Traits\StructureAwareTrait;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class UserProfileSelectRadioItemFactory extends UserProfileSelectFactory
{
    use ServiceLocatorAwareTrait;
    use ContextAwareTrait;
    use StructureAwareTrait;


    /**
     * Create service
     *
     * @param ServiceLocatorInterface $helperPluginManager
     * @return UserProfile
     */
    public function createService(ServiceLocatorInterface $helperPluginManager)
    {
        $this->setServiceLocator( $helperPluginManager->getServiceLocator() );
        $userContextService = $this->getServiceLocator()->get('AuthUserContext');

        $service = new UserProfileSelectRadioItem($userContextService);
        $service
                ->setServiceStructure($this->getServiceStructure())
                ->setStructure($this->getServiceContext()->getStructure());
        
        return $service;
    }
}