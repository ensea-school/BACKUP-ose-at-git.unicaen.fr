<?php

namespace Application\View\Helper\Service;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of LigneFactory
 *
 */
class LigneFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Ligne();
        return $helper;
    }
}