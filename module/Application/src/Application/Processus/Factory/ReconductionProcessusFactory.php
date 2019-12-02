<?php

namespace Application\Processus\Factory;

use Application\Processus\ReconductionProcessus;
use Application\Service\AnneeService;
use Application\Service\CheminPedagogiqueService;
use Application\Service\ContextService;
use Application\Service\ElementPedagogiqueService;
use Application\Service\EtapeService;
use Application\Service\VolumeHoraireEnsService;
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
        $etapeService              = $serviceLocator->get(EtapeService::class);
        $elementPedagogiqueService = $serviceLocator->get(ElementPedagogiqueService::class);
        $cheminPedagogiqueService  = $serviceLocator->get(CheminPedagogiqueService::class);
        $volumeHoraireEnsService   = $serviceLocator->get(VolumeHoraireEnsService::class);
        $anneeService              = $serviceLocator->get(AnneeService::class);
        $contextService            = $serviceLocator->get(ContextService::class);

        $processus = new ReconductionProcessus($etapeService,
            $elementPedagogiqueService,
            $cheminPedagogiqueService,
            $volumeHoraireEnsService,
            $anneeService,
            $contextService);

        return $processus;
    }
}