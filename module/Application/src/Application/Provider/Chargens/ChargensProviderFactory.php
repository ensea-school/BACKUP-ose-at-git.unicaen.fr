<?php

namespace Application\Provider\Chargens;

use Application\Connecteur\Bdd\BddConnecteur;
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
     * @return ChargensProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get(\Application\Constants::BDD); /* @var $em \Doctrine\ORM\EntityManager */

        $bdd = new BddConnecteur();
        $bdd->setEntityManager($em);

        $chargensProvider = new ChargensProvider();
        $chargensProvider->setBdd($bdd);

        return $chargensProvider;
    }
}