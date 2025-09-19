<?php

namespace Application\Service\Traits;

use OffreFormation\Service\CentreCoutEpService;

/**
 * Description of CentreCoutEpServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpServiceAwareTrait
{
    protected ?CentreCoutEpService $serviceCentreCoutEp = null;



    /**
     * @param CentreCoutEpService $serviceCentreCoutEp
     *
     * @return self
     */
    public function setServiceCentreCoutEp(?CentreCoutEpService $serviceCentreCoutEp)
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;

        return $this;
    }



    public function getServiceCentreCoutEp(): ?CentreCoutEpService
    {
        if (empty($this->serviceCentreCoutEp)) {
            $this->serviceCentreCoutEp = \Framework\Application\Application::getInstance()->container()->get(CentreCoutEpService::class);
        }

        return $this->serviceCentreCoutEp;
    }
}