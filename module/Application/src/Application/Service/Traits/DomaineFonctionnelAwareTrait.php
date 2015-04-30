<?php

namespace Application\Service\Traits;

use Application\Service\DomaineFonctionnel;
use Common\Exception\RuntimeException;

trait DomaineFonctionnelAwareTrait
{
    /**
     * description
     *
     * @var DomaineFonctionnel
     */
    private $serviceDomaineFonctionnel;

    /**
     *
     * @param DomaineFonctionnel $serviceDomaineFonctionnel
     * @return self
     */
    public function setServiceDomaineFonctionnel( DomaineFonctionnel $serviceDomaineFonctionnel )
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;
        return $this;
    }

    /**
     *
     * @return DomaineFonctionnel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceDomaineFonctionnel()
    {
        if (empty($this->serviceDomaineFonctionnel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationDomaineFonctionnel');
        }else{
            return $this->serviceDomaineFonctionnel;
        }
    }

}