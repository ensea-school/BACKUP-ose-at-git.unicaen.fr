<?php

namespace Mission\Service;


/**
 * Description of PrimeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PrimeServiceAwareTrait
{
    protected ?PrimeService $servicePrime = null;



    public function getServicePrime (): ?PrimeService
    {
        if (empty($this->servicePrime)) {
            $this->servicePrime = \OseAdmin::instance()->container()->get(PrimeService::class);
        }

        return $this->servicePrime;
    }



    /**
     * @param PrimeService $servicePrime
     *
     * @return self
     */
    public function setServicePrime (?PrimeService $servicePrime)
    {
        $this->servicePrime = $servicePrime;

        return $this;
    }
}