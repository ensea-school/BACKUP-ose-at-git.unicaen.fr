<?php

namespace Application\Service\Traits;

use Application\Service\ContratService;

/**
 * Description of ContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ContratServiceAwareTrait
{
    /**
     * @var ContratService
     */
    private $serviceContrat;



    /**
     * @param ContratService $serviceContrat
     *
     * @return self
     */
    public function setServiceContrat(ContratService $serviceContrat)
    {
        $this->serviceContrat = $serviceContrat;

        return $this;
    }



    /**
     * @return ContratService
     * @throws RuntimeException
     */
    public function getServiceContrat()
    {
        if (empty($this->serviceContrat)) {
            $this->serviceContrat = \Application::$container->get(ContratService::class);
        }

        return $this->serviceContrat;
    }
}