<?php

namespace Enseignement\View\Helper\VolumeHoraire;

use Psr\Container\ContainerInterface;

/**
 * Description of ListeCalendaireFactory
 *
 */
class ListeCalendaireFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $helper = new ListeCalendaireViewHelper();

        return $helper;
    }
}