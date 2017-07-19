<?php

namespace Application\Processus\Factory;

use Application\Processus\IndicateurProcessus;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessusFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return IndicateurProcessus
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $renderer = $serviceLocator->get('view_manager')->getRenderer();
        $mail     = $serviceLocator->get('ControllerPluginManager')->get('mail');

        $processus = new IndicateurProcessus($renderer, $mail);

        return $processus;
    }
}