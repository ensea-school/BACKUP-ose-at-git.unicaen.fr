<?php

namespace Application\View\Helper;

use Application\Service\Traits\ContextServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use Psr\Container\ContainerInterface;
use UnicaenApp\View\Helper\UserProfileSelectFactory;
use UnicaenAuthentification\Service\UserContext;

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
        $userContextService = $container->get(UserContext::class);

        $service = new UserProfileSelectRadioItem($userContextService);
        $service->setServiceStructure($this->getServiceStructure());

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if ($role && $role->getPerimetre() && $role->getPerimetre()->isEtablissement()) {
            $service->setStructure($this->getServiceContext()->getStructure(false));
        }

        return $service;
    }
}