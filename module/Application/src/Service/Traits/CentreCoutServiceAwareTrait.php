<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutService;

/**
 * Description of CentreCoutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutServiceAwareTrait
{
    protected ?CentreCoutService $serviceCentreCout;



    /**
     * @param CentreCoutService|null $serviceCentreCout
     *
     * @return self
     */
    public function setServiceCentreCout( ?CentreCoutService $serviceCentreCout )
    {
        $this->serviceCentreCout = $serviceCentreCout;

        return $this;
    }



    public function getServiceCentreCout(): ?CentreCoutService
    {
        if (!$this->serviceCentreCout){
            $this->serviceCentreCout = \Application::$container->get(CentreCoutService::class);
        }

        return $this->serviceCentreCout;
    }
}