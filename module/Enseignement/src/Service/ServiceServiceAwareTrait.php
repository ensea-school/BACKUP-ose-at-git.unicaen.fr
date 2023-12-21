<?php

namespace Enseignement\Service;

/**
 * Description of ServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceServiceAwareTrait
{
    protected ?ServiceService $serviceService = null;



    /**
     * @param ServiceService $serviceService
     *
     * @return self
     */
    public function setServiceService(?ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;

        return $this;
    }



    public function getServiceService(): ?ServiceService
    {
        if (empty($this->serviceService)) {
            $this->serviceService = \OseAdmin::instance()->container()->get(ServiceService::class);
        }

        return $this->serviceService;
    }
}