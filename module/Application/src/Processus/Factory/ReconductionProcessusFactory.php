<?php

namespace Application\Processus\Factory;

use Application\Processus\ReconductionProcessus;
use Application\Service\AnneeService;
use Application\Service\CheminPedagogiqueService;
use Application\Service\ContextService;
use Application\Service\ElementPedagogiqueService;
use Application\Service\EtapeService;
use Application\Service\VolumeHoraireEnsService;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
class ReconductionProcessusFactory
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $etapeService = $container->get(EtapeService::class);
        $elementPedagogiqueService = $container->get(ElementPedagogiqueService::class);
        $cheminPedagogiqueService = $container->get(CheminPedagogiqueService::class);
        $volumeHoraireEnsService = $container->get(VolumeHoraireEnsService::class);
        $anneeService = $container->get(AnneeService::class);
        $contextService = $container->get(ContextService::class);

        $processus = new ReconductionProcessus($etapeService,
            $elementPedagogiqueService,
            $cheminPedagogiqueService,
            $volumeHoraireEnsService,
            $anneeService,
            $contextService);

        return $processus;
    }
}