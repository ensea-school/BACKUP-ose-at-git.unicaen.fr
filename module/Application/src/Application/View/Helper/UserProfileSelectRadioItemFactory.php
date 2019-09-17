<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenApp\View\Helper\UserProfileSelectFactory;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;

/**
 *
 *
 */
class UserProfileSelectRadioItemFactory extends UserProfileSelectFactory
{
    use ContextServiceAwareTrait;
    use StructureServiceAwareTrait;



    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userContextService = $container->get('AuthUserContext');

        $service = new UserProfileSelectRadioItem($userContextService);
        $service
            ->setServiceStructure($this->getServiceStructure())
            ->setStructure($this->getServiceContext()->getStructure());

        return $service;
    }
}