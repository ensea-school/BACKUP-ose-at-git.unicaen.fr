<?php

namespace Application\Provider\Chargens;

use Application\Service\TypeHeuresService;
use Psr\Container\ContainerInterface;
use Unicaen\Console\Console;
use UnicaenTbl\Service\TableauBordService;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ChargensProviderFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $em = $container->get(\Application\Constants::BDD);
        /* @var $em \Doctrine\ORM\EntityManager */

        $chargensProvider = new ChargensProvider();
        $chargensProvider->setEntityManager($em);

        if (!Console::isConsole()) {
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