<?php

namespace Framework\Authorize;

use BjyAuthorize\Service\Authorize;
use Psr\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Laminas\ServiceManager\Factory\FactoryInterface;
use UnicaenAuthentification\Service\UserContext;

/**
 * Class AssertionFactory
 *
 * @package UnicaenPrivilege\Assertion
 */
class AssertionFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     *
     * @return UserContext
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent    = $application->getMvcEvent();

        /* @var $serviceAuthorize Authorize */
        $serviceAuthorize = $container->get('BjyAuthorize\Service\Authorize');

        /** @var UserContext $serviceUserContext */
        $serviceUserContext = $container->get(UserContext::class);

        /* @var $assertion AbstractAssertion */
        $assertion = new $requestedName;

        $assertion->setMvcEvent($mvcEvent);
        $assertion->setServiceAuthorize($serviceAuthorize);
        $assertion->setServiceUserContext($serviceUserContext);

        return $assertion;
    }
}
