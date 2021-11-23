<?php

namespace Application\Provider\Chargens;

use Application\Connecteur\Bdd\BddConnecteur;
use Application\Service\TypeHeuresService;
use Psr\Container\ContainerInterface;
use UnicaenTbl\Service\TableauBordService;
use Laminas\Console\Console;

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

        $bdd = new BddConnecteur();
        $bdd->setEntityManager($em);

        $chargensProvider = new ChargensProvider();
        $chargensProvider->setBdd($bdd);

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