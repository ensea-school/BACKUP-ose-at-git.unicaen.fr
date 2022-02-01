<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutEpService;

/**
 * Description of CentreCoutEpServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutEpServiceAwareTrait
{
    protected ?CentreCoutEpService $serviceCentreCoutEp;



    /**
     * @param CentreCoutEpService|null $serviceCentreCoutEp
     *
     * @return self
     */
    public function setServiceCentreCoutEp( ?CentreCoutEpService $serviceCentreCoutEp )
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;

        return $this;
    }



    public function getServiceCentreCoutEp(): ?CentreCoutEpService
    {
        if (!$this->serviceCentreCoutEp){
            $this->serviceCentreCoutEp = \Application::$container->get(CentreCoutEpService::class);
        }

        return $this->serviceCentreCoutEp;
    }
}