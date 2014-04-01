<?php

namespace Application\View\Helper\VolumeHoraire;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of ListeFactory
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ListeFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (empty(Liste::$typesIntervention)){
            $tis = $serviceLocator->getServiceLocator()->get('ApplicationTypeIntervention');

            $til = $tis->finderByAll()->getQuery()->execute();

            Liste::$typesIntervention = array();
            foreach( $til as $ti ){
                Liste::$typesIntervention[$ti->getId()] = $ti;
            }
        }
        return new Liste();
    }
}