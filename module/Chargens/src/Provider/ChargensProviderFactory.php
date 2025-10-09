<?php

namespace Chargens\Provider;

use Doctrine\ORM\EntityManager;
use Unicaen\Framework\Authorize\Authorize;
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

        $chargensProvider = new ChargensProvider(
            $container->get(Authorize::class),
        );
        $chargensProvider->setEntityManager($em);
        $chargensProvider->setBdd($container->get(Bdd::class));

        $chargensProvider->setServiceTypeHeures(
            $container->get(TypeHeuresService::class)
        );

        $chargensProvider->setServiceTableauBord($container->get(TableauBordService::class));

        return $chargensProvider;
    }
}