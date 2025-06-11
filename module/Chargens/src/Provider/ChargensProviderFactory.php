<?php

namespace Chargens\Provider;

use Doctrine\ORM\EntityManager;
use OffreFormation\Service\TypeHeuresService;
use Psr\Container\ContainerInterface;
use Unicaen\BddAdmin\Bdd;
use UnicaenTbl\Service\TableauBordService;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ChargensProviderFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $em = $container->get(EntityManager::class);
        /* @var $em \Doctrine\ORM\EntityManager */

        $chargensProvider = new ChargensProvider();
        $chargensProvider->setEntityManager($em);
        $chargensProvider->setBdd($container->get(Bdd::class));

        if ($container->has('BjyAuthorize\Service\Authorize')) {
            $serviceAuthorize = $container->get('BjyAuthorize\Service\Authorize');
            $chargensProvider->setServiceAuthorize($serviceAuthorize);
        }

        $chargensProvider->setServiceTypeHeures(
            $container->get(TypeHeuresService::class)
        );

        $chargensProvider->setServiceTableauBord($container->get(TableauBordService::class));

        return $chargensProvider;
    }
}