<?php

namespace Application\View\Helper\Service;

use Psr\Container\ContainerInterface;

/**
 * Description of ListeFactory
 *
 */
class ListeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Liste();

        return $helper;
    }
}