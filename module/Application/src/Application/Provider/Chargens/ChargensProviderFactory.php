<?php

namespace Application\Provider\Chargens;

use Application\Connecteur\Bdd\BddConnecteur;
use Application\Service\TypeHeuresService;
use UnicaenTbl\Service\TableauBordService;
use Zend\Console\Console;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ChargensProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return ChargensProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get(\Application\Constants::BDD);
        /* @var $em \Doctrine\ORM\EntityManager */

        $bdd = new BddConnecteur();
        $bdd->setEntityManager($em);

        $chargensProvider = new ChargensProvider();
        $chargensProvider->setBdd($bdd);

        if (!Console::isConsole()) {
            $serviceAuthorize = $serviceLocator->get('BjyAuthorize\Service\Authorize');
            $chargensProvider->setServiceAuthorize($serviceAuthorize);
        }

        $chargensProvider->setServiceTypeHeures(
            $serviceLocator->get(TypeHeuresService::class)
        );

        $chargensProvider->setServiceTableauBord($serviceLocator->get(TableauBordService::class));

        return $chargensProvider;
    }
}