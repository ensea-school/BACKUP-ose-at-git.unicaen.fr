<?php

namespace Framework\Authorize;

use Psr\Container\ContainerInterface;
use Laminas\Mvc\Application;
use Laminas\ServiceManager\Factory\FactoryInterface;
use UnicaenAuthentification\Service\UserContext;

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

        /** @var UserContext $serviceUserContext */
        $serviceUserContext = $container->get(UserContext::class);

        /* @var $assertion AbstractAssertion */
        $assertion = new $requestedName(
            $container->get(Authorize::class),
        );

        $assertion->setMvcEvent($mvcEvent);
        $assertion->setServiceUserContext($serviceUserContext);

        return $assertion;
    }
}
