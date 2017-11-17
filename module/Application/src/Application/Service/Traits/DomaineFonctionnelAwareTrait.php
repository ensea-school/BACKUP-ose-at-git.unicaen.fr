<?php

namespace Application\Service\Traits;

use Application\Service\DomaineFonctionnel;

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
     *
     * @return self
     */
    public function setServiceDomaineFonctionnel(DomaineFonctionnel $serviceDomaineFonctionnel)
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;

        return $this;
    }



    /**
     * @return DomaineFonctionnel
     */
    public function getServiceDomaineFonctionnel()
    {
        if (empty($this->serviceDomaineFonctionnel)) {
            $this->serviceDomaineFonctionnel = \Application::$container->get('ApplicationDomaineFonctionnel');
        }

        return $this->serviceDomaineFonctionnel;
    }
}