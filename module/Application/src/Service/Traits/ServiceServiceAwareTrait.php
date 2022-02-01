<?php

namespace Application\Service\Traits;

use Application\Service\ServiceService;

/**
 * Description of ServiceServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceServiceAwareTrait
{
    protected ?ServiceService $serviceService;



    /**
     * @param ServiceService|null $serviceService
     *
     * @return self
     */
    public function setServiceService( ?ServiceService $serviceService )
    {
        $this->serviceService = $serviceService;

        return $this;
    }



    public function getServiceService(): ?ServiceService
    {
        if (!$this->serviceService){
            $this->serviceService = \Application::$container->get(ServiceService::class);
        }

        return $this->serviceService;
    }
}