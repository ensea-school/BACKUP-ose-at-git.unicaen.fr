<?php

namespace Application\View\Helper;


use Interop\Container\ContainerInterface;

/**
 * Description of UserCurrentFactory
 *
 */
class UserCurrentFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authUserContext = $container->get('authUserContext');

        return new UserCurrent($authUserContext);
    }
}