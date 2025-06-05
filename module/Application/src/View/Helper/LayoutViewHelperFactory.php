<?php

namespace Application\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenAuthentification\Options\ModuleOptions;
use UnicaenAuthentification\Service\UserContext;

/**
 * Class LayoutViewHelperFactory
 */
class LayoutViewHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        /** @var UserContext $userContextService */
        $userContextService = $container->get('AuthUserContext');

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $container->get('unicaen-auth_module_options');

        $usurpationAllowed = in_array(
            $userContextService->getIdentityUsername(),
            $moduleOptions->getUsurpationAllowedUsernames());
        $usurpationEnCours = $userContextService->isUsurpationEnCours();
        if ($usurpationEnCours) {
            $usurpationAllowed = true;
        }

        $helper = new LayoutViewHelper();
        $helper->setUsurpationEnabled($usurpationAllowed);
        $helper->setUsurpationEnCours($usurpationEnCours);

        return $helper;
    }
}