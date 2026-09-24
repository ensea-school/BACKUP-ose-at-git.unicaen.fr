<?php

namespace Application\View\Helper;

use Application\Service\NavbarService;
use Unicaen\Framework\Navigation\Navigation;
use Unicaen\Framework\Router\Router;
use Unicaen\Framework\User\UserManager;
use Interop\Container\ContainerInterface;
use Intervenant\Service\IntervenantService;
use Utilisateur\Provider\UserProvider;
use Workflow\Service\WorkflowService;

/**
 * Class LayoutViewHelperFactory
 */
class LayoutViewHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $helper = new LayoutViewHelper(
            $container->get(NavbarService::class),
            $container->get(Navigation::class),
            $container->get(UserManager::class),
            $container->get(Router::class),
            $container->get(UserProvider::class),
            $container->get(WorkflowService::class),
            $container->get(IntervenantService::class),
        );

        return $helper;
    }
}
