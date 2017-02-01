<?php

namespace Application\Provider\Charge;

use Application\Connecteur\Bdd\BddConnecteur;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ChargeProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ChargeProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */

        $bdd = new BddConnecteur();
        $bdd->setEntityManager($em);

        $chargeProvider = new ChargeProvider();
        $chargeProvider->setBdd($bdd);

        return $chargeProvider;
    }
}