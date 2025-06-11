<?php

namespace Enseignement\View\Helper\VolumeHoraire;

use Psr\Container\ContainerInterface;

/**
 * Description of ListeFactory
 *
 */
class ListeFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new ListeViewHelper();

        return $helper;
    }
}