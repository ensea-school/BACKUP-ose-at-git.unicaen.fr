<?php

namespace Application\Service;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class NavigationFactoryFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $navigation = new NavigationFactory();
        return $navigation->createService($container);
    }

}