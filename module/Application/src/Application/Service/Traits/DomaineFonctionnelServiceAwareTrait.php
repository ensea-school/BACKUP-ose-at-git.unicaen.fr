<?php

namespace Application\Service\Traits;

use Application\Service\DomaineFonctionnelService;

/**
 * Description of DomaineFonctionnelAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelServiceAwareTrait
{
    /**
     * @var DomaineFonctionnelService
     */
    private $serviceDomaineFonctionnel;



    /**
     * @param DomaineFonctionnelService $serviceDomaineFonctionnel
     *
     * @return self
     */
    public function setServiceDomaineFonctionnel(DomaineFonctionnelService $serviceDomaineFonctionnel)
    {
        $this->serviceDomaineFonctionnel = $serviceDomaineFonctionnel;

        return $this;
    }



    /**
     * @return DomaineFonctionnelService
     */
    public function getServiceDomaineFonctionnel()
    {
        if (empty($this->serviceDomaineFonctionnel)) {
            $this->serviceDomaineFonctionnel = \Application::$container->get(DomaineFonctionnelService::class);
        }

        return $this->serviceDomaineFonctionnel;
    }
}