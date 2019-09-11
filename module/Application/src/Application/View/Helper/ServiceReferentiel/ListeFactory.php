<?php

namespace Application\View\Helper\ServiceReferentiel;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Description of ListeFactory
 *
 */
class ListeFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Liste();

        return $helper;
    }
}