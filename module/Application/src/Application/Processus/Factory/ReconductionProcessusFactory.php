<?php

namespace Application\Processus\Factory;

use Application\Entity\Db\ElementPedagogique;
use Application\Processus\ReconductionProcessus;
use Application\Service\AnneeService;
use Application\Service\CheminPedagogiqueService;
use Application\Service\ContextService;
use Application\Service\ElementPedagogiqueService;
use Application\Service\EtapeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
class ReconductionProcessusFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ReconductionProcessus
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $etapeService = \Application::$container->get(EtapeService::class);
        $elementPedagogiqueService = \Application::$container->get(ElementPedagogiqueService::class);
        $cheminPedagogiqueService = \Application::$container->get(CheminPedagogiqueService::class);
        $anneeService = \Application::$container->get(AnneeService::class);
        $contextService = \Application::$container->get(ContextService::class);
        $processus = new ReconductionProcessus($etapeService, $elementPedagogiqueService, $cheminPedagogiqueService, $anneeService, $contextService);

        return $processus;
    }
}