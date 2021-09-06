<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuth\Options\ModuleOptions;
use UnicaenAuth\View\Helper\LocalConnectViewHelper;

/**
 * Class LocalConnectViewHelperFactory
 */
class LocalConnectViewHelperFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return LocalConnectViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('unicaen-auth_module_options');
        $config        = $moduleOptions->getDb();

        $enabled     = isset($config['enabled']) && (bool)$config['enabled'];
        $description = $config['description'] ?? null;

        $helper = new LocalConnectViewHelper();
        $helper->setEnabled($enabled);
        $helper->setDescription($description);
        $helper->setPasswordReset(false);

        return $helper;
    }
}