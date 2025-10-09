<?php

namespace Application\View\Helper;

use Application\Service\NavbarService;
use Unicaen\Framework\Navigation\Navigation;
use Unicaen\Framework\User\UserManager;
use Interop\Container\ContainerInterface;

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
        );

        return $helper;
    }
}