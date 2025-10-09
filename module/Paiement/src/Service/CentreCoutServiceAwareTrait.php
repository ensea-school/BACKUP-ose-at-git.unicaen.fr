<?php

namespace Paiement\Service;


/**
 * Description of CentreCoutServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutServiceAwareTrait
{
    protected ?CentreCoutService $serviceCentreCout = null;



    /**
     * @param CentreCoutService $serviceCentreCout
     *
     * @return self
     */
    public function setServiceCentreCout(?CentreCoutService $serviceCentreCout)
    {
        $this->serviceCentreCout = $serviceCentreCout;

        return $this;
    }



    public function getServiceCentreCout(): ?CentreCoutService
    {
        if (empty($this->serviceCentreCout)) {
            $this->serviceCentreCout = \Unicaen\Framework\Application\Application::getInstance()->container()->get(CentreCoutService::class);
        }

        return $this->serviceCentreCout;
    }
}