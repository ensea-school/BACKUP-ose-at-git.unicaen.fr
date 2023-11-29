<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Options\ModuleOptions;
use UnicaenAuthentification\View\Helper\LocalConnectViewHelper;

/**
 * Class LocalConnectViewHelperFactory
 */
class LocalConnectViewHelperFactory
{
    /**
     * @param ContainerInterface $container
     * @return LocalConnectViewHelper
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('unicaen-auth_module_options');
        $config = $moduleOptions->getLocal();

        $enabled = isset($config['enabled']) && (bool) $config['enabled'];
        $title = 'Avec un compte LDAP ou local';
        $description = $config['description'] ?? null;

        $helper = new LocalConnectViewHelper();
        $helper->setEnabled($enabled);
        $helper->setTitle($title);
        $helper->setDescription($description);
        $helper->setPasswordReset(false);

        return $helper;
    }
}