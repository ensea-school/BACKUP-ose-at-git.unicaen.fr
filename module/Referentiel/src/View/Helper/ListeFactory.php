<?php

namespace Referentiel\View\Helper;


use Psr\Container\ContainerInterface;

/**
 * Description of ListeFactory
 *
 */
class ListeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new ListeViewHelper();

        return $helper;
    }
}