<?php

namespace Application\Service\Traits;

use Application\Service\DomaineFonctionnel;
use Application\Module;
use RuntimeException;

/**
 * Description of DomaineFonctionnelAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelAwareTrait
{
    /**
     * @var DomaineFonctionnel
     */
    private $serviceDomaineFonctionnel;





    /**
     * @param DomaineFonctionnel $serviceDomaineFonctionnel
     * @return self
     */
    public function setServiceDomaineFonctionnel( DomaineFonctionnel $serviceDomaineFonctionnel )
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;
        return $this;
    }



    /**
     * @return DomaineFonctionnel
     * @throws RuntimeException
     */
    public function getServiceDomaineFonctionnel()
    {
        if (empty($this->serviceDomaineFonctionnel)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceDomaineFonctionnel = $serviceLocator->get('ApplicationDomaineFonctionnel');
        }
        return $this->serviceDomaineFonctionnel;
    }
}