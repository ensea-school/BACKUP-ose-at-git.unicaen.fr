<?php

namespace Application\Processus\Factory;

use Application\Processus\IndicateurProcessus;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessusFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $renderer = $container->get('view_manager')->getRenderer();
        $mail     = $container->get('ControllerPluginManager')->get('mail');

        $processus = new IndicateurProcessus($renderer, $mail);

        return $processus;
    }

}