<?php

namespace Indicateur\Processus;

use Psr\Container\ContainerInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessusFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $renderer = $container->get('ViewHelperManager')->getRenderer();
        $mail     = $container->get('ControllerPluginManager')->get('mail');

        $processus = new IndicateurProcessus($renderer, $mail);

        return $processus;
    }

}