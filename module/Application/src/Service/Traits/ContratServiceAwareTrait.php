<?php

namespace Application\Service\Traits;

use Application\Service\ContratService;

/**
 * Description of ContratServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratServiceAwareTrait
{
    protected ?ContratService $serviceContrat = null;



    /**
     * @param ContratService $serviceContrat
     *
     * @return self
     */
    public function setServiceContrat(?ContratService $serviceContrat)
    {
        $this->serviceContrat = $serviceContrat;

        return $this;
    }



    public function getServiceContrat(): ?ContratService
    {
        if (empty($this->serviceContrat)) {
            $this->serviceContrat = \Application::$container->get(ContratService::class);
        }

        return $this->serviceContrat;
    }
}