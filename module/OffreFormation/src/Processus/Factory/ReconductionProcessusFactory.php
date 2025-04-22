<?php

namespace OffreFormation\Processus\Factory;

use Application\Service\AnneeService;
use Application\Service\ContextService;
use OffreFormation\Service\CheminPedagogiqueService;
use OffreFormation\Service\ElementPedagogiqueService;
use OffreFormation\Service\EtapeService;
use OffreFormation\Service\VolumeHoraireEnsService;
use OffreFormation\Processus\ReconductionProcessus;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;

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

        $processus->setBdd($container->get(Bdd::class));

        return $processus;
    }
}