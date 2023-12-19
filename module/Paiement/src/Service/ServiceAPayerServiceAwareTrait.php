<?php

namespace Paiement\Service;


/**
 * Description of ServiceAPayerServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceAPayerServiceAwareTrait
{
    protected ?ServiceAPayerService $serviceServiceAPayer = null;



    /**
     * @param ServiceAPayerService $serviceServiceAPayer
     *
     * @return self
     */
    public function setServiceServiceAPayer(?ServiceAPayerService $serviceServiceAPayer)
    {
        $this->serviceServiceAPayer = $serviceServiceAPayer;

        return $this;
    }



    public function getServiceServiceAPayer(): ?ServiceAPayerService
    {
        if (empty($this->serviceServiceAPayer)) {
            $this->serviceServiceAPayer = \OseAdmin::instance()->container()->get(ServiceAPayerService::class);
        }

        return $this->serviceServiceAPayer;
    }
}