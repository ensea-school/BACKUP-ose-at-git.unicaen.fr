<?php

namespace Application\View\Helper\Service;


use Psr\Container\ContainerInterface;

/**
 * Description of LigneFactory
 *
 */
class LigneFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new Ligne();

        return $helper;
    }
}