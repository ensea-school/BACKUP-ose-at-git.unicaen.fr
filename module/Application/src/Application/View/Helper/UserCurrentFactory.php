<?php

namespace Application\View\Helper;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of UserCurrentFactory
 *
 */
class UserCurrentFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $authUserContext = $container->get('authUserContext');

        return new UserCurrent($authUserContext);
    }
}