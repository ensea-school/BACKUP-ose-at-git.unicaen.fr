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
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\StructureAwareTrait
    ;

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