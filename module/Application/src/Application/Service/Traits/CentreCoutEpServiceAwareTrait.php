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
    /**
     * @var CentreCoutEpService
     */
    private $serviceCentreCoutEp;



    /**
     * @param CentreCoutEpService $serviceCentreCoutEp
     *
     * @return self
     */
    public function setServiceCentreCoutEp(CentreCoutEpService $serviceCentreCoutEp)
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;

        return $this;
    }



    /**
     * @return CentreCoutEpService
     */
    public function getServiceCentreCoutEp()
    {
        if (empty($this->serviceCentreCoutEp)) {
            $this->serviceCentreCoutEp = \Application::$container->get(CentreCoutEpService::class);
        }

        return $this->serviceCentreCoutEp;
    }
}