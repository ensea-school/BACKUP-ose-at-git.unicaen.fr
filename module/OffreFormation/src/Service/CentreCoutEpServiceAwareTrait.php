<?php

namespace OffreFormation\Service;


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
            $this->serviceCentreCoutEp = \Application::$container->get(CentreCoutEpService::class);
        }

        return $this->serviceCentreCoutEp;
    }
}